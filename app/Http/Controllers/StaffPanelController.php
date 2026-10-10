<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff panel (store keeper): read-only view of stock and incoming purchases.
 * Purchase prices and money totals are intentionally not shown to staff.
 */
class StaffPanelController extends Controller
{
    public function dashboard(): View
    {
        $active = Product::where('status', true);

        $lowStock = (clone $active)
            ->whereColumn('stock', '<=', 'alert_quantity')
            ->where('stock', '>', 0);

        return view('backend.staff.dashboard', [
            'totalProducts' => (clone $active)->count(),
            'totalUnits' => (float) (clone $active)->sum('stock'),
            'lowStockCount' => (clone $lowStock)->count(),
            'outOfStockCount' => (clone $active)->where('stock', '<=', 0)->count(),
            'lowStockProducts' => (clone $active)
                ->with('unit')
                ->whereColumn('stock', '<=', 'alert_quantity')
                ->orderBy('stock')
                ->limit(8)
                ->get(),
            'recentPurchases' => Purchase::with('supplier')
                ->withSum('items', 'quantity')
                ->withCount('items')
                ->where('status', '!=', 'cancelled')
                ->orderByDesc('purchase_date')
                ->latest('id')
                ->limit(5)
                ->get(),
        ]);
    }


    /**
     * Stock list with search and filters.
     */
    public function stock(Request $request): View
    {
        $products = Product::with(['category', 'brand', 'unit'])
            ->where('status', true)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%');
                });
            })
            ->when($request->category_id, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($request->stock === 'low', fn ($query) => $query->whereColumn('stock', '<=', 'alert_quantity')->where('stock', '>', 0))
            ->when($request->stock === 'out', fn ($query) => $query->where('stock', '<=', 0))
            ->when($request->stock === 'in', fn ($query) => $query->whereColumn('stock', '>', 'alert_quantity'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('backend.staff.stock', compact('products', 'categories'));
    }


    /**
     * Incoming stock (purchases) without prices.
     */
    public function purchases(Request $request): View
    {
        $purchases = Purchase::with('supplier')
            ->withSum('items', 'quantity')
            ->withCount('items')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('purchase_no', 'like', '%' . $search . '%')
                        ->orWhereHas('supplier', fn ($query) => $query->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('purchase_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.staff.purchases', compact('purchases'));
    }


    public function purchaseShow($id): View
    {
        $purchase = Purchase::with(['supplier', 'items.product.unit'])->findOrFail($id);

        return view('backend.staff.purchase_show', compact('purchase'));
    }
}
