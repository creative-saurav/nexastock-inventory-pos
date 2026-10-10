@extends('backend.layouts.admin')

@section('page-title', 'Reports')
@section('title', 'Reports')


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">

        <h4 class="mb-1 fw-semibold">
            Reports
        </h4>

        <p class="text-muted mb-0">
            Analyze sales, profit, purchases, expenses and stock.
        </p>

    </div>


    @php
        $reports = [
            [
                'route' => 'reports.sales',
                'title' => 'Sales Report',
                'text'  => 'Invoices, discounts and daily sales totals, by cashier and payment method.',
                'icon'  => 'bi-receipt',
                'tone'  => 'primary',
            ],
            [
                'route' => 'reports.profit_loss',
                'title' => 'Profit & Loss',
                'text'  => 'Net sales, cost of goods sold, expenses and net profit for any period.',
                'icon'  => 'bi-graph-up-arrow',
                'tone'  => 'success',
            ],
            [
                'route' => 'reports.products',
                'title' => 'Product Sales',
                'text'  => 'Quantity sold, sales value, cost and profit for each product.',
                'icon'  => 'bi-box-seam',
                'tone'  => 'info',
            ],
            [
                'route' => 'reports.purchases',
                'title' => 'Purchase Report',
                'text'  => 'Purchases by supplier, with paid and due amounts.',
                'icon'  => 'bi-bag-plus',
                'tone'  => 'warning',
            ],
            [
                'route' => 'reports.expenses',
                'title' => 'Expense Report',
                'text'  => 'Where the money went, grouped by expense category.',
                'icon'  => 'bi-wallet2',
                'tone'  => 'danger',
            ],
            [
                'route' => 'reports.stock',
                'title' => 'Stock Report',
                'text'  => 'Current stock, low and out-of-stock items, and stock value.',
                'icon'  => 'bi-boxes',
                'tone'  => 'secondary',
            ],
        ];
    @endphp


    <div class="row g-3">

        @foreach($reports as $report)

            <div class="col-md-6 col-xl-4">

                <a href="{{ route($report['route']) }}" class="card border-0 shadow-sm h-100 text-decoration-none text-reset">

                    <div class="card-body p-4 d-flex gap-3">

                        <span class="badge bg-{{ $report['tone'] }}-subtle text-{{ $report['tone'] }} rounded-3 p-3 align-self-start">
                            <i class="bi {{ $report['icon'] }} fs-4"></i>
                        </span>

                        <div class="flex-grow-1">

                            <h6 class="fw-bold mb-1">
                                {{ $report['title'] }}
                            </h6>

                            <p class="text-muted small mb-2">
                                {{ $report['text'] }}
                            </p>

                            <span class="small fw-semibold text-primary">
                                View report <i class="bi bi-arrow-right"></i>
                            </span>

                        </div>

                    </div>

                </a>

            </div>

        @endforeach

    </div>

</div>

@endsection
