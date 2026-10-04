<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display products.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::with([
            'category',
            'brand',
            'unit',
        ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%');

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'backend.products.index',
            compact('products')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        $units = Unit::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'backend.products.create',
            compact(
                'categories',
                'brands',
                'units'
            )
        );
    }


    /**
     * Store product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'unit_id' => [
                'required',
                'exists:units,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,barcode',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'alert_quantity' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        $validated['slug'] = Str::slug($validated['name']);


        /*
        |--------------------------------------------------------------------------
        | Upload Product Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $uploadPath = public_path('uploads/products');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $request->file('image');

            $imageName = time() . '_' .
                Str::slug($validated['name']) .
                '.' .
                $image->getClientOriginalExtension();

            $image->move($uploadPath, $imageName);

            $validated['image'] =
                'uploads/products/' . $imageName;
        }


        Product::create($validated);


        Session::flash(
            'success',
            get_phrase('Product created successfully!')
        );

        return redirect()->back();
    }


    public function edit($id)
        {
            $product = Product::findOrFail($id);

            $categories = Category::where('status', true)
                ->orderBy('name')
                ->get();

            $brands = Brand::where('status', true)
                ->orderBy('name')
                ->get();

            $units = Unit::where('status', true)
                ->orderBy('name')
                ->get();

            return view(
                'backend.products.edit',
                compact(
                    'product',
                    'categories',
                    'brands',
                    'units'
                )
            );
        }


    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);


    $validated = $request->validate([

        'category_id' => [
            'required',
            'exists:categories,id',
        ],

        'brand_id' => [
            'nullable',
            'exists:brands,id',
        ],

        'unit_id' => [
            'required',
            'exists:units,id',
        ],

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'purchase_price' => [
            'required',
            'numeric',
            'min:0',
        ],

        'selling_price' => [
            'required',
            'numeric',
            'min:0',
        ],

        'alert_quantity' => [
            'required',
            'numeric',
            'min:0',
        ],

        'description' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'boolean',
        ],

        'image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],

    ]);


    /*
    |--------------------------------------------------------------------------
    | Generate Unique Slug
    |--------------------------------------------------------------------------
    */

    $baseSlug = Str::slug($validated['name']);

    $slug = $baseSlug;

    $counter = 1;

    while (
        Product::where('slug', $slug)
            ->where('id', '!=', $product->id)
            ->exists()
    ) {

        $slug = $baseSlug . '-' . $counter;

        $counter++;
    }

    $validated['slug'] = $slug;


    /*
    |--------------------------------------------------------------------------
    | Upload Product Image
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('image')) {

        /*
        |--------------------------------------------------------------------------
        | Delete Old Image
        |--------------------------------------------------------------------------
        */

        if ($product->image) {

            $oldImage = public_path($product->image);

            if (file_exists($oldImage)) {
                unlink($oldImage);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create Upload Directory
        |--------------------------------------------------------------------------
        */

        $uploadPath = public_path('uploads/products');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }


        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        $image = $request->file('image');

        $imageName = time() . '_' .
            Str::slug($validated['name']) .
            '.' .
            $image->getClientOriginalExtension();

        $image->move($uploadPath, $imageName);

        $validated['image'] =
            'uploads/products/' . $imageName;

    } else {

        /*
        |--------------------------------------------------------------------------
        | Keep Existing Image
        |--------------------------------------------------------------------------
        */

        $validated['image'] = $product->image;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Product
    |--------------------------------------------------------------------------
    |
    | SKU, Barcode and Stock are not updated here.
    |
    */

    $product->update($validated);


    /*
    |--------------------------------------------------------------------------
    | Success Message
    |--------------------------------------------------------------------------
    */

    Session::flash(
        'success',
        get_phrase('Product updated successfully!')
    );

    return redirect()->back();
}

 public function delete($id)
    {
        $product = Product::findOrFail($id);


        if ($product->logo) {

            $image = public_path($product->image);

            if (file_exists($image)) {
                unlink($image);
            }
        }


        $product->delete();

        Session::flash(
            'success',
            get_phrase('Product deleted successfully!')
        );

        return redirect()->back();
    }


}