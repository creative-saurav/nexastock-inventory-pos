<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public shop website: browse products and prices, no online ordering.
 * Only active products in active categories are shown, and purchase prices are never exposed.
 */
class FrontendController extends Controller
{
    public function home(): View
    {
        $latestProducts = $this->visibleProducts()->latest()->limit(12)->get();

        // Best sellers come from real POS sales
        $bestSellerIds = SaleItem::query()
            ->selectRaw('product_id, SUM(quantity) as sold')
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->limit(8)
            ->pluck('product_id');

        $bestSellers = $this->visibleProducts()
            ->whereIn('id', $bestSellerIds)
            ->get()
            ->sortBy(fn ($product) => $bestSellerIds->search($product->id))
            ->values();

        return view('frontend.home', [
            'categories' => $this->categoriesWithCount()->limit(6)->get(),
            'latestProducts' => $latestProducts,
            'showcase' => $latestProducts->whereNotNull('image')->take(3)->values(),
            'bestSellers' => $bestSellers,
            'brands' => $this->brandsWithProducts()->orderBy('name')->limit(12)->get(),
            'productCount' => $this->visibleProducts()->count(),
            'categoryCount' => $this->categoriesWithCount()->count(),
            'brandCount' => $this->brandsWithProducts()->count(),
        ]);
    }


    public function products(Request $request): View
    {
        $category = $request->filled('category')
            ? Category::where('status', true)->where('slug', $request->category)->first()
            : null;

        $brand = $request->filled('brand')
            ? Brand::where('status', true)->where('slug', $request->brand)->first()
            : null;

        $products = $this->visibleProducts()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%');
                });
            })
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->when($brand, fn ($query) => $query->where('brand_id', $brand->id))
            ->when($request->boolean('in_stock'), fn ($query) => $query->where('stock', '>', 0))
            ->when($request->sort, function ($query, $sort) {
                match ($sort) {
                    'price_low' => $query->orderBy('selling_price'),
                    'price_high' => $query->orderByDesc('selling_price'),
                    'name' => $query->orderBy('name'),
                    default => $query->latest(),
                };
            }, fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('frontend.products.index', [
            'products' => $products,
            'categories' => $this->categoriesWithCount()->get(),
            'brands' => $this->brandsWithProducts()->orderBy('name')->get(),
            'activeCategory' => $category,
            'activeBrand' => $brand,
        ]);
    }


    public function product(string $slug): View
    {
        $product = $this->visibleProducts()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = $this->visibleProducts()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('frontend.products.show', compact('product', 'related'));
    }


    public function contact(): View
    {
        return view('frontend.contact');
    }


    /**
     * Active products whose category is also active.
     */
    private function visibleProducts()
    {
        return Product::query()
            ->with(['category', 'brand', 'unit'])
            ->where('status', true)
            ->whereHas('category', fn ($query) => $query->where('status', true));
    }

    private function brandsWithProducts()
    {
        return Brand::where('status', true)
            ->whereIn('id', $this->visibleProducts()->select('brand_id'));
    }

    private function categoriesWithCount()
    {
        return Category::where('status', true)
            ->whereHas('products', fn ($query) => $query->where('status', true))
            ->withCount(['products' => fn ($query) => $query->where('status', true)])
            ->orderBy('name');
    }
}
