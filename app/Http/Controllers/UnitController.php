<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UnitController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->search;

        $units = Unit::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%');

                });

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.units.index', compact('units'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('backend.units.create');
    }


    /**
     * Store a newly created Unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'short_name' => ['required', 'string', 'max:20'],
            'status' => ['required', 'boolean'],
        ]);


        Unit::create($validated);

        Session::flash(
            'success',
            get_phrase('Unit created successfully!')
        );

        return redirect()->back();
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);

        return view('backend.units.edit', compact('unit'));
    }


    /**
     * Update Unit.
     */
    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name,' . $unit->id],
            'short_name' => ['required', 'string', 'max:20'],
            'status' => ['required', 'boolean'],
        ]);


        $unit->update($validated);

        Session::flash(
            'success',
            get_phrase('Unit updated successfully!')
        );

        return redirect()->back();
    }


    /**
     * Delete Unit.
     */
    public function delete($id)
    {
        $unit = Unit::findOrFail($id);

        if (Product::where('unit_id', $unit->id)->exists()) {

            Session::flash(
                'error',
                get_phrase('This unit is used by products. Remove or change them first.')
            );

            return redirect()->back();
        }

        $unit->delete();

        Session::flash(
            'success',
            get_phrase('Unit deleted successfully!')
        );

        return redirect()->back();
    }
}
