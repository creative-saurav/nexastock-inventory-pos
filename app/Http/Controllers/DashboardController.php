<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => abort(403, 'Invalid user role.'),
        };
    }

    /**
     * Full business overview (admin & manager).
     */
    public function admin(): View
    {
        return view('backend.admin.dashboard', $this->overview());
    }

    public function manager(): View
    {
        return view('backend.manager.dashboard', $this->overview());
    }

    /**
     * Cashier sees only their own sales.
     */
    public function cashier(): View
    {
        $userId = auth()->id();

        $today = Sale::where('user_id', $userId)->whereDate('created_at', today());
        $month = Sale::where('user_id', $userId)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);

        return view('backend.cashier.dashboard', [
            'todaySales' => (clone $today)->sum('grand_total'),
            'todayCount' => (clone $today)->count(),
            'monthSales' => (clone $month)->sum('grand_total'),
            'monthCount' => (clone $month)->count(),
            // Today's money by payment method, for end-of-day cash counting
            'todayByMethod' => (clone $today)
                ->selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
                ->groupBy('payment_method')
                ->pluck('total', 'payment_method'),
            'chart' => $this->dailySales(Sale::where('user_id', $userId)),
            'recentSales' => Sale::with('customer')
                ->where('user_id', $userId)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }


    private function overview(): array
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $todaySales = Sale::whereDate('created_at', today());
        $monthSales = Sale::whereBetween('created_at', [$monthStart, $monthEnd]);

        $monthRevenue = (clone $monthSales)->sum('grand_total');

        // Cost of goods sold this month (purchase price saved at the time of sale)
        $monthCost = SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->whereBetween('sales.created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('sale_items.quantity * sale_items.cost_price'));

        $monthExpenses = Expense::whereBetween('expense_date', [
            $monthStart->toDateString(),
            $monthEnd->toDateString(),
        ])->sum('amount');

        $lowStockQuery = Product::where('status', true)
            ->whereColumn('stock', '<=', 'alert_quantity');

        return [
            'todaySales' => (clone $todaySales)->sum('grand_total'),
            'todayCount' => (clone $todaySales)->count(),
            'monthRevenue' => $monthRevenue,
            'monthCount' => (clone $monthSales)->count(),
            'monthExpenses' => $monthExpenses,
            'monthProfit' => $monthRevenue - $monthCost - $monthExpenses,
            'monthPurchases' => Purchase::whereBetween('purchase_date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])->sum('grand_total'),

            'totalProducts' => Product::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'lowStockCount' => (clone $lowStockQuery)->count(),

            'chart' => $this->dailySales(Sale::query()),

            'lowStockProducts' => (clone $lowStockQuery)
                ->with('unit')
                ->orderBy('stock')
                ->limit(6)
                ->get(),

            'topProducts' => SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->join('products', 'products.id', '=', 'sale_items.product_id')
                ->whereBetween('sales.created_at', [$monthStart, $monthEnd])
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->select(
                    'products.name',
                    'products.sku',
                    DB::raw('SUM(sale_items.quantity) as qty'),
                    DB::raw('SUM(sale_items.total) as revenue')
                )
                ->orderByDesc('qty')
                ->limit(5)
                ->get(),

            'recentSales' => Sale::with(['customer', 'user'])
                ->latest()
                ->limit(6)
                ->get(),
        ];
    }

    /**
     * Daily sales total for the last 14 days (days with no sales are 0).
     */
    private function dailySales($query, int $days = 14): array
    {
        $from = today()->subDays($days - 1);

        $totals = $query
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, SUM(grand_total) as total, COUNT(*) as count')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $values = [];
        $counts = [];

        foreach (CarbonPeriod::create($from, today()) as $date) {
            $key = $date->toDateString();

            $labels[] = $date->format('d M');
            $values[] = round((float) ($totals[$key]->total ?? 0), 2);
            $counts[] = (int) ($totals[$key]->count ?? 0);
        }

        return compact('labels', 'values', 'counts');
    }
}
