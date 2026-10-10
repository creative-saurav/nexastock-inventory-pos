<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Customer portal: a logged-in customer sees only their own purchases.
 */
class CustomerPanelController extends Controller
{
    public function dashboard(): View
    {
        $customerId = auth()->id();

        $purchases = Sale::where('customer_id', $customerId);

        $itemsBought = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.customer_id', $customerId)
            ->sum('sale_items.quantity');

        return view('customer.dashboard', [
            'totalPurchases' => (clone $purchases)->count(),
            'totalSpent' => (clone $purchases)->sum('grand_total'),
            'totalSaved' => (clone $purchases)->sum('discount'),
            'itemsBought' => (int) $itemsBought,
            'lastPurchase' => (clone $purchases)->latest()->first(),
            'recentPurchases' => (clone $purchases)
                ->withSum('items', 'quantity')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }


    /**
     * All purchases of the logged-in customer.
     */
    public function purchases(Request $request): View
    {
        $purchases = Sale::where('customer_id', auth()->id())
            ->when($request->search, fn ($query, $search) => $query->where('invoice_no', 'like', '%' . $search . '%'))
            ->when($request->from, fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->to, fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->withSum('items', 'quantity')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customer.purchases.index', compact('purchases'));
    }


    public function show($id): View
    {
        $sale = $this->findOwnSale($id);

        return view('customer.purchases.show', compact('sale'));
    }


    /**
     * Printable invoice (same design as the admin invoice).
     */
    public function invoice($id): View
    {
        $sale = $this->findOwnSale($id);

        $sale->load('items.product.unit');

        return view('backend.sales.invoice', compact('sale'));
    }


    /**
     * 404 (not 403) for another customer's invoice, so invoice IDs can't be probed.
     */
    private function findOwnSale($id): Sale
    {
        return Sale::with(['customer', 'user', 'items.product'])
            ->where('customer_id', auth()->id())
            ->findOrFail($id);
    }
}
