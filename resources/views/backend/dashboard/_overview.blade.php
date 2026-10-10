{{-- Business overview shared by admin & manager dashboards --}}

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-semibold mb-1">
                Welcome back, {{ auth()->user()->name }}
            </h4>

            <p class="text-muted mb-0">
                Here's how your shop is doing today, {{ now()->format('l, d F Y') }}.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('pos') }}" class="btn btn-primary">
                <i class="bi bi-cart-plus me-1"></i>
                New Sale
            </a>

            <a href="{{ route('purchases') }}" class="btn btn-light border">
                <i class="bi bi-bag-plus me-1"></i>
                Purchases
            </a>

        </div>

    </div>



    {{-- KEY NUMBERS --}}
    <div class="row g-3 mb-4">

        @php
            $cards = [
                [
                    'label' => "Today's Sales",
                    'value' => '৳' . number_format($todaySales, 2),
                    'sub'   => $todayCount . ' sale(s) today',
                    'icon'  => 'bi-cash-coin',
                    'tone'  => 'primary',
                ],
                [
                    'label' => 'This Month Sales',
                    'value' => '৳' . number_format($monthRevenue, 2),
                    'sub'   => $monthCount . ' sale(s) in ' . now()->format('F'),
                    'icon'  => 'bi-graph-up-arrow',
                    'tone'  => 'success',
                ],
                [
                    'label' => 'This Month Expenses',
                    'value' => '৳' . number_format($monthExpenses, 2),
                    'sub'   => 'Purchases: ৳' . number_format($monthPurchases, 2),
                    'icon'  => 'bi-wallet2',
                    'tone'  => 'warning',
                ],
                [
                    'label' => 'Est. Net Profit (Month)',
                    'value' => ($monthProfit < 0 ? '- ' : '') . '৳' . number_format(abs($monthProfit), 2),
                    'sub'   => 'Sales − product cost − expenses',
                    'icon'  => $monthProfit < 0 ? 'bi-arrow-down-right' : 'bi-arrow-up-right',
                    'tone'  => $monthProfit < 0 ? 'danger' : 'info',
                ],
            ];
        @endphp

        @foreach($cards as $card)

            <div class="col-md-6 col-xl-3">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-start gap-3">

                        <span class="badge bg-{{ $card['tone'] }}-subtle text-{{ $card['tone'] }} rounded-3 p-3">
                            <i class="bi {{ $card['icon'] }} fs-5"></i>
                        </span>

                        <div class="min-w-0">
                            <small class="text-muted d-block">{{ $card['label'] }}</small>
                            <h4 class="fw-bold mb-1">{{ $card['value'] }}</h4>
                            <small class="text-muted">{{ $card['sub'] }}</small>
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>



    {{-- CHART + QUICK STATS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-8">
            @include('backend.dashboard._sales_chart')
        </div>


        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">At a Glance</h6>
                </div>

                <div class="list-group list-group-flush">

                    <a href="{{ route('products') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-box-seam me-2 text-primary"></i>Total Products</span>
                        <span class="fw-bold">{{ $totalProducts }}</span>
                    </a>

                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-people me-2 text-primary"></i>Customers</span>
                        <span class="fw-bold">{{ $totalCustomers }}</span>
                    </div>

                    <a href="{{ route('products') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Low Stock Items</span>
                        @if($lowStockCount > 0)
                            <span class="badge bg-danger">{{ $lowStockCount }}</span>
                        @else
                            <span class="badge bg-success-subtle text-success">All good</span>
                        @endif
                    </a>

                    <a href="{{ route('sales') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-receipt me-2 text-primary"></i>Sales This Month</span>
                        <span class="fw-bold">{{ $monthCount }}</span>
                    </a>

                    <a href="{{ route('purchases') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <span><i class="bi bi-bag me-2 text-primary"></i>Purchases This Month</span>
                        <span class="fw-bold">৳{{ number_format($monthPurchases, 2) }}</span>
                    </a>

                </div>

            </div>

        </div>

    </div>



    {{-- TOP PRODUCTS + LOW STOCK --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Top Selling Products</h6>
                    <small class="text-muted">This month, by quantity sold</small>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Qty Sold</th>
                                <th class="text-end pe-4" title="Before invoice discount">Gross Sales</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td class="text-center">{{ (int) $product->qty }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($product->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        No sales this month yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Low Stock Alert</h6>
                    <small class="text-muted">Stock at or below the alert quantity</small>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">In Stock</th>
                                <th class="text-end pe-4">Alert At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($product->stock <= 0)
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-x-circle me-1"></i>Out of stock
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                {{ rtrim(rtrim(number_format($product->stock, 2), '0'), '.') }} {{ $product->unit?->short_name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 text-muted">
                                        {{ rtrim(rtrim(number_format($product->alert_quantity, 2), '0'), '.') }} {{ $product->unit?->short_name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle text-success me-1"></i>
                                        All products have enough stock.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>



    {{-- RECENT SALES --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Recent Sales</h6>
            <a href="{{ route('sales') }}" class="btn btn-sm btn-light border">View All</a>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Invoice No.</th>
                        <th>Customer</th>
                        <th>Sold By</th>
                        <th>Time</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('sales.show', $sale->id) }}" class="fw-semibold text-decoration-none">
                                    {{ $sale->invoice_no }}
                                </a>
                            </td>
                            <td>{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $sale->user?->name ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $sale->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-4 fw-semibold">৳{{ number_format($sale->grand_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No sales yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>
