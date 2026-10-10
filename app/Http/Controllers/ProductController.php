<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $validated['slug'] = unique_slug(Product::class, $validated['name']);


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

        'stock' => [
            'required',
            'numeric',
            'min:0',
        ],

        'original_stock' => [
            'required',
            'numeric',
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
    | Stock Change
    |--------------------------------------------------------------------------
    |
    | Only the difference the user typed is applied, so sales made while the
    | form was open are not overwritten.
    |
    */

    $stockChange = round((float) $validated['stock'] - (float) $validated['original_stock'], 2);

    unset($validated['stock'], $validated['original_stock']);

    // Check before touching the image, so a rejected save doesn't delete the old photo
    if ($stockChange != 0 && (float) $product->stock + $stockChange < 0) {

        Session::flash(
            'error',
            'Stock changed while you were editing (current: ' . (float) $product->stock . '). Please reopen the product and try again.'
        );

        return redirect()->back();
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Unique Slug
    |--------------------------------------------------------------------------
    */

    $validated['slug'] = unique_slug(Product::class, $validated['name'], $product->id);


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
    | SKU and Barcode are not updated here.
    |
    */

    try {

        DB::transaction(function () use ($product, $validated, $stockChange) {

            $product->update($validated);

            if ($stockChange != 0) {

                // Lock the row so a sale at the same moment can't race this change
                $locked = Product::lockForUpdate()->findOrFail($product->id);

                $newStock = round((float) $locked->stock + $stockChange, 2);

                if ($newStock < 0) {
                    throw new \RuntimeException(
                        'Stock changed while you were editing (current: ' . (float) $locked->stock . '). Please reopen the product and try again.'
                    );
                }

                $locked->update(['stock' => $newStock]);
            }
        });

    } catch (\RuntimeException $e) {

        Session::flash('error', $e->getMessage());

        return redirect()->back();
    }


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


        if ($product->image) {

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