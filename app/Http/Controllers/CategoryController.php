<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $categories = Category::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.categories.index', compact('categories'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('backend.categories.create');
    }


    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = unique_slug(Category::class, $validated['name']);

        Category::create($validated);

        Session::flash(
            'success',
            get_phrase('Category created successfully!')
        );

        return redirect()->back();
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.categories.edit', compact('category'));
    }


    /**
     * Update category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = unique_slug(Category::class, $validated['name'], $category->id);

        $category->update($validated);

        Session::flash(
            'success',
            get_phrase('Category updated successfully!')
        );

        return redirect()->back();
    }


    /**
     * Delete category.
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);

        if (Product::where('category_id', $category->id)->exists()) {

            Session::flash(
                'error',
                get_phrase('This category has products. Remove or move them first.')
            );

            return redirect()->back();
        }

        $category->delete();

        Session::flash(
            'success',
            get_phrase('Category deleted successfully!')
        );

        return redirect()->back();
    }
}