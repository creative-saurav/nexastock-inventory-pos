<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Display purchase list.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $purchases = Purchase::with('supplier')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where(
                        'purchase_no',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas('supplier', function ($query) use ($search) {

                        $query->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );

                    });

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'backend.purchases.index',
            compact('purchases')
        );
    }


    /**
     * Show create purchase form.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', true)
            ->orderBy('name')
            ->get();

        $products = Product::with([
            'category',
            'brand',
            'unit',
        ])
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'backend.purchases.create',
            compact(
                'suppliers',
                'products'
            )
        );
    }


    /**
     * Store new purchase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
                'distinct',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'items.*.purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:received,pending',
            ],

        ], [
            'items.*.product_id.distinct' => get_phrase('The same product has been added more than once.'),
        ]);


        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Calculate Purchase Amounts
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($validated['items'] as $item) {

                $itemSubtotal =
                    (float) $item['quantity'] *
                    (float) $item['purchase_price'];

                $subtotal += $itemSubtotal;
            }


            $discount = (float) ($validated['discount'] ?? 0);

            if ($discount > $subtotal) {
                $discount = $subtotal;
            }


            $grandTotal =
                $subtotal - $discount;


            $paidAmount =
                (float) ($validated['paid_amount'] ?? 0);


            if ($paidAmount > $grandTotal) {
                $paidAmount = $grandTotal;
            }


            $dueAmount =
                $grandTotal - $paidAmount;


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            if ($dueAmount <= 0) {

                $paymentStatus = 'paid';

            } elseif ($paidAmount > 0) {

                $paymentStatus = 'partial';

            } else {

                $paymentStatus = 'due';
            }


            /*
            |--------------------------------------------------------------------------
            | Create Purchase
            |--------------------------------------------------------------------------
            |
            | purchase_no (PUR-20261005-0001) is generated
            | automatically in the Purchase model.
            |
            */

            $purchase = Purchase::create([

                'supplier_id' => $validated['supplier_id'],

                'purchase_date' => $validated['purchase_date'],

                'subtotal' => $subtotal,

                'discount' => $discount,

                'grand_total' => $grandTotal,

                'paid_amount' => $paidAmount,

                'due_amount' => $dueAmount,

                'payment_status' => $paymentStatus,

                'status' => $validated['status'],

                'note' => $validated['note'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Purchase Items
            |--------------------------------------------------------------------------
            */

            foreach ($validated['items'] as $item) {

                $quantity =
                    (float) $item['quantity'];

                $purchasePrice =
                    (float) $item['purchase_price'];

                $itemSubtotal =
                    $quantity * $purchasePrice;


                PurchaseItem::create([

                    'purchase_id' =>
                        $purchase->id,

                    'product_id' =>
                        $item['product_id'],

                    'quantity' =>
                        $quantity,

                    'purchase_price' =>
                        $purchasePrice,

                    'subtotal' =>
                        $itemSubtotal,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Increase Stock
                |--------------------------------------------------------------------------
                |
                | Only received purchases increase inventory.
                |
                */

                if ($validated['status'] === 'received') {

                    $product = Product::lockForUpdate()
                        ->findOrFail($item['product_id']);

                    $product->increment(
                        'stock',
                        $quantity
                    );
                }
            }

        });


        Session::flash(
            'success',
            get_phrase('Purchase created successfully!')
        );

        return redirect()->route('purchases');
    }


    /**
     * Show purchase details.
     */
    public function show($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'items.product.unit',
        ])->findOrFail($id);

        return view(
            'backend.purchases.show',
            compact('purchase')
        );
    }


    /**
     * Show edit purchase form.
     */
    public function edit($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'items.product',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Suppliers & Products
        |--------------------------------------------------------------------------
        |
        | The purchase's own supplier and products are included even if
        | they are inactive now, so they stay selected in the edit form.
        |
        */

        $suppliers = Supplier::where('status', true)
            ->orWhere('id', $purchase->supplier_id)
            ->orderBy('name')
            ->get();

        $products = Product::with([
            'category',
            'brand',
            'unit',
        ])
            ->where(function ($query) use ($purchase) {

                $query->where('status', true)
                    ->orWhereIn(
                        'id',
                        $purchase->items->pluck('product_id')
                    );

            })
            ->orderBy('name')
            ->get();

        return view(
            'backend.purchases.edit',
            compact(
                'purchase',
                'suppliers',
                'products'
            )
        );
    }


    /**
     * Update purchase.
     */
    public function update(Request $request, $id)
    {
        $purchase = Purchase::with('items')
            ->findOrFail($id);


        $validated = $request->validate([

            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
                'distinct',
            ],

            'items.*.quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'items.*.purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:received,pending,cancelled',
            ],

        ], [
            'items.*.product_id.distinct' => get_phrase('The same product has been added more than once.'),
        ]);


        DB::transaction(function () use (
            $validated,
            $purchase
        ) {

            /*
            |--------------------------------------------------------------------------
            | Calculate Stock Change Per Product
            |--------------------------------------------------------------------------
            |
            | Instead of reversing the whole old purchase and adding the new
            | one, only the net difference (new qty - old qty) is applied.
            | This way, editing a purchase whose stock is partly sold
            | (e.g. only changing note or price) still works.
            |
            */

            $stockChanges = [];

            if ($purchase->status === 'received') {

                foreach ($purchase->items as $oldItem) {

                    $stockChanges[$oldItem->product_id] =
                        ($stockChanges[$oldItem->product_id] ?? 0) -
                        (float) $oldItem->quantity;
                }
            }

            if ($validated['status'] === 'received') {

                foreach ($validated['items'] as $item) {

                    $stockChanges[$item['product_id']] =
                        ($stockChanges[$item['product_id']] ?? 0) +
                        (float) $item['quantity'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Apply Stock Change
            |--------------------------------------------------------------------------
            |
            | If some of this stock has already been sold, reducing it
            | could make stock negative, so the update is stopped.
            |
            */

            foreach ($stockChanges as $productId => $change) {

                $change = round($change, 2);

                if ($change == 0) {
                    continue;
                }

                $product = Product::lockForUpdate()
                    ->findOrFail($productId);

                if (round((float) $product->stock + $change, 2) < 0) {

                    throw ValidationException::withMessages([
                        'items' => get_phrase('Cannot update this purchase because some purchased stock of') . ' ' . $product->name . ' ' . get_phrase('has already been used.'),
                    ]);
                }

                if ($change > 0) {

                    $product->increment('stock', $change);

                } else {

                    $product->decrement('stock', abs($change));
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Calculate New Amounts
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($validated['items'] as $item) {

                $itemSubtotal =
                    (float) $item['quantity'] *
                    (float) $item['purchase_price'];

                $subtotal += $itemSubtotal;
            }


            $discount =
                (float) ($validated['discount'] ?? 0);


            if ($discount > $subtotal) {
                $discount = $subtotal;
            }


            $grandTotal =
                $subtotal - $discount;


            $paidAmount =
                (float) ($validated['paid_amount'] ?? 0);


            if ($paidAmount > $grandTotal) {
                $paidAmount = $grandTotal;
            }


            $dueAmount =
                $grandTotal - $paidAmount;


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            if ($dueAmount <= 0) {

                $paymentStatus = 'paid';

            } elseif ($paidAmount > 0) {

                $paymentStatus = 'partial';

            } else {

                $paymentStatus = 'due';
            }


            /*
            |--------------------------------------------------------------------------
            | Update Purchase
            |--------------------------------------------------------------------------
            */

            $purchase->update([

                'supplier_id' =>
                    $validated['supplier_id'],

                'purchase_date' =>
                    $validated['purchase_date'],

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'grand_total' =>
                    $grandTotal,

                'paid_amount' =>
                    $paidAmount,

                'due_amount' =>
                    $dueAmount,

                'payment_status' =>
                    $paymentStatus,

                'status' =>
                    $validated['status'],

                'note' =>
                    $validated['note'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Delete Old Items
            |--------------------------------------------------------------------------
            */

            $purchase->items()->delete();


            /*
            |--------------------------------------------------------------------------
            | Create New Items
            |--------------------------------------------------------------------------
            |
            | Stock was already applied above.
            |
            */

            foreach ($validated['items'] as $item) {

                $quantity =
                    (float) $item['quantity'];

                $purchasePrice =
                    (float) $item['purchase_price'];

                $itemSubtotal =
                    $quantity * $purchasePrice;


                PurchaseItem::create([

                    'purchase_id' =>
                        $purchase->id,

                    'product_id' =>
                        $item['product_id'],

                    'quantity' =>
                        $quantity,

                    'purchase_price' =>
                        $purchasePrice,

                    'subtotal' =>
                        $itemSubtotal,

                ]);
            }

        });


        Session::flash(
            'success',
            get_phrase('Purchase updated successfully!')
        );

        return redirect()->route('purchases');
    }


    /**
     * Delete purchase.
     */
    public function delete($id)
    {
        $purchase = Purchase::with('items')
            ->findOrFail($id);


        DB::transaction(function () use ($purchase) {

            /*
            |--------------------------------------------------------------------------
            | Reverse Stock Before Delete
            |--------------------------------------------------------------------------
            */

            if ($purchase->status === 'received') {

                foreach ($purchase->items as $item) {

                    $product = Product::lockForUpdate()
                        ->findOrFail($item->product_id);


                    if (
                        (float) $product->stock <
                        (float) $item->quantity
                    ) {

                        throw ValidationException::withMessages([
                            'items' => get_phrase('Cannot delete this purchase because some purchased stock of') . ' ' . $product->name . ' ' . get_phrase('has already been used.'),
                        ]);
                    }


                    $product->decrement(
                        'stock',
                        $item->quantity
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Purchase
            |--------------------------------------------------------------------------
            |
            | purchase_items are deleted automatically because
            | purchase_id uses cascadeOnDelete().
            |
            */

            $purchase->delete();

        });


        Session::flash(
            'success',
            get_phrase('Purchase deleted successfully!')
        );

        return redirect()->back();
    }

    

}