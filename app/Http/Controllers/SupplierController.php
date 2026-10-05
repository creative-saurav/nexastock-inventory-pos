<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    /**
     * Display supplier list.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $suppliers = Supplier::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'backend.suppliers.index',
            compact('suppliers')
        );
    }


    /**
     * Show create supplier form.
     */
    public function create()
    {
        return view('backend.suppliers.create');
    }


    /**
     * Store new supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        Supplier::create($validated);

        Session::flash(
            'success',
            get_phrase('Supplier created successfully!')
        );

        return redirect()->route('suppliers');
    }


    /**
     * Show edit supplier form.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view(
            'backend.suppliers.edit',
            compact('supplier')
        );
    }


    /**
     * Update supplier.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

        ]);

        $supplier->update($validated);

        Session::flash(
            'success',
            get_phrase('Supplier updated successfully!')
        );

        return redirect()->route('suppliers');
    }


    /**
     * Delete supplier.
     */
    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        Session::flash(
            'success',
            get_phrase('Supplier deleted successfully!')
        );

        return redirect()->back();
    }
}