<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public const PRESETS = [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'this_week' => 'This Week',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'this_year' => 'This Year',
    ];

    public function index(): View
    {
        return view('backend.reports.index');
    }


    /**
     * Sales report: invoices in a date range, with daily breakdown.
     */
    public function sales(Request $request): View
    {
        [$from, $to] = $this->range($request);

        $query = Sale::whereBetween('created_at', [$from, $to])
            ->when($request->user_id, fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($request->payment_method, fn ($query, $method) => $query->where('payment_method', $method));

        $summary = (clone $query)->selectRaw('
            COUNT(*) as count,
            COALESCE(SUM(subtotal), 0) as subtotal,
            COALESCE(SUM(discount), 0) as discount,
            COALESCE(SUM(grand_total), 0) as grand_total
        ')->first();

        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count, SUM(subtotal) as subtotal, SUM(discount) as discount, SUM(grand_total) as grand_total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $byPayment = (clone $query)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $sales = (clone $query)
            ->with(['customer', 'user'])
            ->withSum('items', 'quantity')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $users = User::whereIn('role', ['admin', 'manager', 'cashier'])->orderBy('name')->get();

        return view('backend.reports.sales', compact('from', 'to', 'summary', 'daily', 'byPayment', 'sales', 'users'));
    }


    /**
     * Profit & loss for a date range.
     */
    public function profitLoss(Request $request): View
    {
        [$from, $to] = $this->range($request);

        $sales = Sale::whereBetween('created_at', [$from, $to])->selectRaw('
            COUNT(*) as count,
            COALESCE(SUM(subtotal), 0) as subtotal,
            COALESCE(SUM(discount), 0) as discount,
            COALESCE(SUM(grand_total), 0) as grand_total
        ')->first();

        $cogs = (float) SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$from, $to])
            ->sum(DB::raw('sale_items.quantity * sale_items.cost_price'));

        $expensesByCategory = Expense::join('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->selectRaw('expense_categories.name, COUNT(*) as count, SUM(expenses.amount) as total')
            ->orderByDesc('total')
            ->get();

        $netSales = (float) $sales->grand_total;
        $grossProfit = $netSales - $cogs;
        $totalExpenses = (float) $expensesByCategory->sum('total');
        $netProfit = $grossProfit - $totalExpenses;

        $purchases = (float) Purchase::whereBetween('purchase_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', '!=', 'cancelled')
            ->sum('grand_total');

        return view('backend.reports.profit_loss', compact(
            'from', 'to', 'sales', 'cogs', 'netSales', 'grossProfit',
            'expensesByCategory', 'totalExpenses', 'netProfit', 'purchases'
        ));
    }


    /**
     * Product-wise sales: quantity, sales value, cost and profit per product.
     */
    public function products(Request $request): View
    {
        [$from, $to] = $this->range($request);

        $rows = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->whereBetween('sales.created_at', [$from, $to])
            ->when($request->category_id, fn ($query, $categoryId) => $query->where('products.category_id', $categoryId))
            ->groupBy('products.id', 'products.name', 'products.sku', 'categories.name')
            ->selectRaw('
                products.name,
                products.sku,
                categories.name as category,
                SUM(sale_items.quantity) as qty,
                SUM(sale_items.total) as sales_value,
                SUM(sale_items.quantity * sale_items.cost_price) as cost
            ')
            ->orderByDesc('sales_value')
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('backend.reports.products', compact('from', 'to', 'rows', 'categories'));
    }


    /**
     * Purchase report with supplier filter and dues.
     */
    public function purchases(Request $request): View
    {
        [$from, $to] = $this->range($request);

        // Columns are table-qualified because the by-supplier query joins suppliers (which also has `status`)
        $query = Purchase::whereBetween('purchases.purchase_date', [$from->toDateString(), $to->toDateString()])
            ->where('purchases.status', '!=', 'cancelled')
            ->when($request->supplier_id, fn ($query, $supplierId) => $query->where('purchases.supplier_id', $supplierId))
            ->when($request->payment_status, fn ($query, $status) => $query->where('purchases.payment_status', $status));

        $summary = (clone $query)->selectRaw('
            COUNT(*) as count,
            COALESCE(SUM(grand_total), 0) as grand_total,
            COALESCE(SUM(paid_amount), 0) as paid_amount,
            COALESCE(SUM(due_amount), 0) as due_amount
        ')->first();

        $bySupplier = (clone $query)
            ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->selectRaw('suppliers.name, COUNT(*) as count, SUM(purchases.grand_total) as total, SUM(purchases.due_amount) as due')
            ->orderByDesc('total')
            ->get();

        $purchases = (clone $query)
            ->with('supplier')
            ->orderByDesc('purchase_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get();

        return view('backend.reports.purchases', compact('from', 'to', 'summary', 'bySupplier', 'purchases', 'suppliers'));
    }


    /**
     * Expense report grouped by category.
     */
    public function expenses(Request $request): View
    {
        [$from, $to] = $this->range($request);

        $query = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->when($request->category_id, fn ($query, $categoryId) => $query->where('expense_category_id', $categoryId));

        $total = (float) (clone $query)->sum('amount');
        $count = (clone $query)->count();

        $byCategory = (clone $query)
            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->groupBy('expense_categories.id', 'expense_categories.name')
            ->selectRaw('expense_categories.name, COUNT(*) as count, SUM(expenses.amount) as total')
            ->orderByDesc('total')
            ->get();

        $expenses = (clone $query)
            ->with(['category', 'user'])
            ->orderByDesc('expense_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('backend.reports.expenses', compact('from', 'to', 'total', 'count', 'byCategory', 'expenses', 'categories'));
    }


    /**
     * Current stock and its value (not date based).
     */
    public function stock(Request $request): View
    {
        $query = Product::with(['category', 'unit'])
            ->when($request->category_id, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($request->stock === 'low', fn ($query) => $query->whereColumn('stock', '<=', 'alert_quantity')->where('stock', '>', 0))
            ->when($request->stock === 'out', fn ($query) => $query->where('stock', '<=', 0))
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('sku', 'like', '%' . $search . '%');
                });
            });

        $summary = (clone $query)->selectRaw('
            COUNT(*) as count,
            COALESCE(SUM(stock), 0) as units,
            COALESCE(SUM(stock * purchase_price), 0) as cost_value,
            COALESCE(SUM(stock * selling_price), 0) as sale_value
        ')->first();

        $products = $query->orderBy('name')->paginate(25)->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('backend.reports.stock', compact('summary', 'products', 'categories'));
    }


    /**
     * Resolve the date range from a preset or from/to inputs. Defaults to this month.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function range(Request $request): array
    {
        $preset = $request->input('preset');

        if (! $preset && ! $request->filled('from') && ! $request->filled('to')) {
            $preset = 'this_month';
        }

        [$from, $to] = match ($preset) {
            'today' => [today(), today()],
            'yesterday' => [today()->subDay(), today()->subDay()],
            'this_week' => [now()->startOfWeek(), today()],
            'this_month' => [now()->startOfMonth(), today()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'this_year' => [now()->startOfYear(), today()],
            default => [
                $this->parseDate($request->from) ?? now()->startOfMonth(),
                $this->parseDate($request->to) ?? today(),
            ],
        };

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        return [$from->copy()->startOfDay(), $to->copy()->endOfDay()];
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
