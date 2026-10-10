<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display POS screen.
     */

    public function index(Request $request)
    {
        $search = $request->search;

        $sales = $this->visibleSales()
            ->with(['customer', 'user'])
            ->withCount('items')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('invoice_no', 'like', '%' . $search . '%')
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')
            ->where('status', 1)
            ->latest()
            ->get();

        return view('backend.sales.create', compact('customers'));
    }

    /**
     * Store a completed sale.
     */
  public function store(Request $request)
{
    $request->validate([
        'customer_id'      => 'nullable|exists:users,id',
        'cart'             => 'required|array|min:1',
        'cart.*.id'        => 'required|exists:products,id',
        'cart.*.quantity'  => 'required|integer|min:1',
        'discount'         => 'required|numeric|min:0',
        'paid_amount'      => 'required|numeric|min:0',
        'payment_method'   => 'required|in:cash,card,mobile_banking',
    ]);

    try {

        $sale = DB::transaction(function () use ($request) {

            // Lock products and calculate totals from DB prices (never trust client prices)
            $subtotal = 0;
            $items = [];

            foreach ($request->cart as $cartItem) {

                $product = Product::lockForUpdate()->findOrFail($cartItem['id']);
                $quantity = (int) $cartItem['quantity'];

                if ($product->stock < $quantity) {
                    throw new \RuntimeException("Not enough stock for {$product->name}.");
                }

                $total = $product->selling_price * $quantity;
                $subtotal += $total;

                $items[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'price'    => $product->selling_price,
                    'cost'     => $product->purchase_price,
                    'total'    => $total,
                ];
            }

            $discount = min((float) $request->discount, $subtotal);
            $grandTotal = round($subtotal - $discount, 2);
            $paidAmount = round((float) $request->paid_amount, 2);

            if ($paidAmount < $grandTotal) {
                throw new \RuntimeException('Paid amount is less than the total amount.');
            }

            $sale = Sale::create([
                'invoice_no'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'customer_id'    => $request->customer_id ?: null,
                'user_id'        => auth()->id(),
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => 0,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $paidAmount,
                'change_amount'  => $paidAmount - $grandTotal,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
            ]);

            foreach ($items as $item) {

                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'cost_price' => $item['cost'],
                    'total'      => $item['total'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $sale;
        });

    } catch (\RuntimeException $e) {

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage(),
        ], 422);
    }

    return response()->json([

        'status' => true,

        'message' => 'Sale created successfully.',

        'sale_id' => $sale->id,

        'invoice_no' => $sale->invoice_no,

    ]);
}

    public function searchProducts(Request $request)
    {
        $search = $request->search;

        $products = Product::where('name', 'like', '%' . $search . '%')
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function show($id){
        $sale = $this->visibleSales()
            ->with(['customer', 'user', 'items.product'])
            ->findOrFail($id);

        return view('backend.sales.show', compact('sale'));
    }

    /**
     * Printable invoice (standalone page, no admin layout).
     */
    public function invoice($id)
    {
        $sale = $this->visibleSales()
            ->with(['customer', 'user', 'items.product.unit'])
            ->findOrFail($id);

        return view('backend.sales.invoice', compact('sale'));
    }

    /**
     * Quick-add a customer from the POS (name + phone is enough).
     * If a customer with the same phone already exists, that one is returned instead.
     */
    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:users,email',
        ]);

        $existing = User::where('role', 'customer')
            ->where('phone', $validated['phone'])
            ->first();

        if ($existing) {
            return response()->json([
                'status'   => true,
                'existing' => true,
                'message'  => 'A customer with this phone already exists. Selected ' . $existing->name . '.',
                'customer' => $existing->only(['id', 'name', 'phone']),
            ]);
        }

        $customer = User::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'email'    => $validated['email'] ?? null,
            'password' => null,
            'role'     => 'customer',
            'status'   => 1,
        ]);

        return response()->json([
            'status'   => true,
            'existing' => false,
            'message'  => 'Customer ' . $customer->name . ' added.',
            'customer' => $customer->only(['id', 'name', 'phone']),
        ]);
    }

    /**
     * Cashiers only see their own sales; admin and manager see all.
     * Another cashier's sale gives 404 so invoice IDs can't be probed.
     */
    private function visibleSales()
    {
        return Sale::query()->when(
            auth()->user()->hasRole('cashier'),
            fn ($query) => $query->where('user_id', auth()->id())
        );
    }

    

}
