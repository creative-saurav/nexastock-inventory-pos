<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'card' => 'Card',
        'mobile_banking' => 'Mobile Banking',
        'bank_transfer' => 'Bank Transfer',
    ];

    public function index(Request $request)
    {
        $search = $request->search;

        $query = Expense::query()
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('expense_no', 'like', '%' . $search . '%')
                        ->orWhere('reference', 'like', '%' . $search . '%')
                        ->orWhere('note', 'like', '%' . $search . '%');

                });

            })
            ->when($request->category, fn ($query, $category) => $query->where('expense_category_id', $category))
            ->when($request->from, fn ($query, $from) => $query->whereDate('expense_date', '>=', $from))
            ->when($request->to, fn ($query, $to) => $query->whereDate('expense_date', '<=', $to));

        // Total of all filtered expenses (not just the current page)
        $totalAmount = (clone $query)->sum('amount');

        $expenses = $query
            ->with(['category', 'user'])
            ->orderByDesc('expense_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = ExpenseCategory::orderBy('name')->get();

        $thisMonthTotal = Expense::whereBetween('expense_date', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ])->sum('amount');

        $todayTotal = Expense::whereDate('expense_date', now()->toDateString())->sum('amount');

        $isFiltered = collect(['search', 'category', 'from', 'to'])
            ->contains(fn ($key) => $request->filled($key));

        return view('backend.expenses.index', compact(
            'expenses',
            'categories',
            'totalAmount',
            'thisMonthTotal',
            'todayTotal',
            'isFiltered'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', true)->orderBy('name')->get();
        $paymentMethods = self::PAYMENT_METHODS;

        return view('backend.expenses.create', compact('categories', 'paymentMethods'));
    }


    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // Transaction keeps the daily expense number gap-free
        DB::transaction(function () use ($validated) {
            Expense::create($validated + ['user_id' => auth()->id()]);
        });

        Session::flash(
            'success',
            get_phrase('Expense added successfully!')
        );

        return redirect()->back();
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        // Keep the current category selectable even if it was deactivated later
        $categories = ExpenseCategory::where('status', true)
            ->orWhere('id', $expense->expense_category_id)
            ->orderBy('name')
            ->get();

        $paymentMethods = self::PAYMENT_METHODS;

        return view('backend.expenses.edit', compact('expense', 'categories', 'paymentMethods'));
    }


    /**
     * Update expense.
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate($this->rules());

        $expense->update($validated);

        Session::flash(
            'success',
            get_phrase('Expense updated successfully!')
        );

        return redirect()->back();
    }


    /**
     * Delete expense.
     */
    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        $expense->delete();

        Session::flash(
            'success',
            get_phrase('Expense deleted successfully!')
        );

        return redirect()->back();
    }


    private function rules(): array
    {
        return [
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999'],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_method' => ['required', Rule::in(array_keys(self::PAYMENT_METHODS))],
            'reference' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
