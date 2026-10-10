<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $categories = ExpenseCategory::query()
            ->withCount('expenses')
            ->withSum('expenses', 'amount')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.expense_categories.index', compact('categories'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('backend.expense_categories.create');
    }


    /**
     * Store a newly created expense category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expense_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'boolean'],
        ]);

        ExpenseCategory::create($validated);

        Session::flash(
            'success',
            get_phrase('Expense category created successfully!')
        );

        return redirect()->back();
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        return view('backend.expense_categories.edit', compact('category'));
    }


    /**
     * Update expense category.
     */
    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expense_categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'boolean'],
        ]);

        $category->update($validated);

        Session::flash(
            'success',
            get_phrase('Expense category updated successfully!')
        );

        return redirect()->back();
    }


    /**
     * Delete expense category.
     */
    public function delete($id)
    {
        $category = ExpenseCategory::findOrFail($id);

        if ($category->expenses()->exists()) {

            Session::flash(
                'error',
                get_phrase('This category has expenses. Remove or move them first.')
            );

            return redirect()->back();
        }

        $category->delete();

        Session::flash(
            'success',
            get_phrase('Expense category deleted successfully!')
        );

        return redirect()->back();
    }
}
