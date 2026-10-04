<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $brands = Brand::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.brands.index', compact('brands'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('backend.brands.create');
    }


    /**
     * Store a newly created Brand.
     */
    public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'description' => ['nullable', 'string'],
                'status' => ['required', 'boolean'],
            ]);

            $validated['slug'] = Str::slug($validated['name']);


            /*
            |--------------------------------------------------------------------------
            | Upload Brand Logo
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('logo')) {

                $uploadPath = public_path('uploads/brands');

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::slug($validated['name'])
                    . '.' . $logo->getClientOriginalExtension();

                $logo->move($uploadPath, $logoName);

                $validated['logo'] = 'uploads/brands/' . $logoName;
            }


            Brand::create($validated);

            Session::flash(
                'success',
                get_phrase('Brand created successfully!')
            );

            return redirect()->back();
        }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('backend.brands.edit', compact('brand'));
    }


    /**
     * Update Brand.
     */
   public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);


        /*
        |--------------------------------------------------------------------------
        | Upload New Brand Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $uploadPath = public_path('uploads/brands');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Logo
            |--------------------------------------------------------------------------
            */

            if ($brand->logo) {

                $oldLogo = public_path($brand->logo);

                if (file_exists($oldLogo)) {
                    unlink($oldLogo);
                }

            }


            /*
            |--------------------------------------------------------------------------
            | Store New Logo
            |--------------------------------------------------------------------------
            */

            $logo = $request->file('logo');

            $logoName = time() . '_' . Str::slug($validated['name'])
                . '.' . $logo->getClientOriginalExtension();

            $logo->move($uploadPath, $logoName);

            $validated['logo'] = 'uploads/brands/' . $logoName;
        }


        $brand->update($validated);

        Session::flash(
            'success',
            get_phrase('Brand updated successfully!')
        );

        return redirect()->back();
    }

    /**
     * Delete Brand.
     */
    public function delete($id)
        {
            $brand = Brand::findOrFail($id);


            if ($brand->logo) {

                $logo = public_path($brand->logo);

                if (file_exists($logo)) {
                    unlink($logo);
                }
            }


            $brand->delete();

            Session::flash(
                'success',
                get_phrase('Brand deleted successfully!')
            );

            return redirect()->back();
        }


}
