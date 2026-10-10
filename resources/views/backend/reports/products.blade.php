@extends('backend.layouts.admin')

@section('page-title', 'Product Sales Report')
@section('title', 'Product Sales Report')


@section('content')

@php
    $totalQty = $rows->sum('qty');
    $totalValue = $rows->sum('sales_value');
    $totalCost = $rows->sum('cost');
    $totalProfit = $totalValue - $totalCost;
@endphp

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Product Sales Report',
        'subtitle' => 'Sales and profit per product',
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.products') }}" method="GET">

                <div class="row g-2 align-items-end">

                    @include('backend.reports._dates')


                    <div class="col-lg-2 col-md-4">

                        <label class="form-label small text-muted mb-1">Category</label>

                        <select name="category_id" class="form-select">

                            <option value="">All Categories</option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- SUMMARY --}}
    <div class="row g-3 mb-4 report-stats">

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Products Sold', 'value' => $rows->count(), 'sub' => number_format($totalQty) . ' unit(s)', 'icon' => 'bi-box-seam', 'tone' => 'primary'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Sales Value', 'value' => '৳' . number_format($totalValue, 2), 'sub' => 'Before invoice discount', 'icon' => 'bi-cash-stack', 'tone' => 'info'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Cost', 'value' => '৳' . number_format($totalCost, 2), 'icon' => 'bi-bag', 'tone' => 'warning'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', [
                'label' => 'Product Profit',
                'value' => ($totalProfit < 0 ? '- ' : '') . '৳' . number_format(abs($totalProfit), 2),
                'sub' => 'Before invoice discount',
                'icon' => 'bi-graph-up-arrow',
                'tone' => $totalProfit < 0 ? 'danger' : 'success',
            ])
        </div>

    </div>



    <div class="card border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th class="text-center">Qty Sold</th>
                        <th class="text-end">Sales Value</th>
                        <th class="text-end">Cost</th>
                        <th class="text-end">Profit</th>
                        <th class="text-end pe-4">Margin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                        @php
                            $profit = $row->sales_value - $row->cost;
                            $rowMargin = $row->sales_value > 0 ? ($profit / $row->sales_value) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $row->name }}</div>
                                <small class="text-muted">{{ $row->sku }}</small>
                            </td>
                            <td>{{ $row->category ?? 'N/A' }}</td>
                            <td class="text-center">{{ (int) $row->qty }}</td>
                            <td class="text-end">৳{{ number_format($row->sales_value, 2) }}</td>
                            <td class="text-end">৳{{ number_format($row->cost, 2) }}</td>
                            <td class="text-end fw-semibold {{ $profit < 0 ? 'text-danger' : 'text-success' }}">
                                {{ $profit < 0 ? '- ' : '' }}৳{{ number_format(abs($profit), 2) }}
                            </td>
                            <td class="text-end pe-4">{{ number_format($rowMargin, 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No products sold in this period.</td>
                        </tr>
                    @endforelse
                </tbody>

                @if($rows->isNotEmpty())
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td class="ps-4" colspan="3">Total</td>
                            <td class="text-center">{{ (int) $totalQty }}</td>
                            <td class="text-end">৳{{ number_format($totalValue, 2) }}</td>
                            <td class="text-end">৳{{ number_format($totalCost, 2) }}</td>
                            <td class="text-end">{{ $totalProfit < 0 ? '- ' : '' }}৳{{ number_format(abs($totalProfit), 2) }}</td>
                            <td class="text-end pe-4">{{ $totalValue > 0 ? number_format(($totalProfit / $totalValue) * 100, 1) : '0.0' }}%</td>
                        </tr>
                    </tfoot>
                @endif

            </table>

        </div>

    </div>

    <p class="text-muted small mt-3 mb-0">
        <i class="bi bi-info-circle me-1"></i>
        Discounts are given on the whole invoice, so they are not split across products here.
        See <a href="{{ route('reports.profit_loss', request()->only(['preset', 'from', 'to'])) }}">Profit &amp; Loss</a> for profit after discounts and expenses.
    </p>

</div>

@endsection
