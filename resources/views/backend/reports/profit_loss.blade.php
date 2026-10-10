@extends('backend.layouts.admin')

@section('page-title', 'Profit & Loss')
@section('title', 'Profit & Loss')


@section('content')

@php
    $money = fn ($value) => ($value < 0 ? '- ' : '') . '৳' . number_format(abs($value), 2);
    $margin = fn ($value) => $netSales > 0 ? number_format(($value / $netSales) * 100, 1) . '% of net sales' : null;
@endphp

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Profit & Loss',
        'subtitle' => 'Income statement',
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.profit_loss') }}" method="GET">

                <div class="row g-2 align-items-end">

                    @include('backend.reports._dates')

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
            @include('backend.reports._stat', ['label' => 'Net Sales', 'value' => $money($netSales), 'sub' => $sales->count . ' invoice(s)', 'icon' => 'bi-cash-stack', 'tone' => 'primary'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Gross Profit', 'value' => $money($grossProfit), 'sub' => $margin($grossProfit), 'icon' => 'bi-bar-chart', 'tone' => $grossProfit < 0 ? 'danger' : 'info'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Expenses', 'value' => $money($totalExpenses), 'icon' => 'bi-wallet2', 'tone' => 'warning'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', [
                'label' => $netProfit < 0 ? 'Net Loss' : 'Net Profit',
                'value' => $money($netProfit),
                'sub' => $margin($netProfit),
                'icon' => $netProfit < 0 ? 'bi-arrow-down-right' : 'bi-arrow-up-right',
                'tone' => $netProfit < 0 ? 'danger' : 'success',
            ])
        </div>

    </div>



    <div class="row g-3">

        {{-- STATEMENT --}}
        <div class="col-xl-7">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Statement</h6>
                </div>

                <table class="table align-middle mb-0">

                    <tbody>

                        <tr>
                            <td class="ps-4">Gross Sales</td>
                            <td class="text-end pe-4">{{ $money($sales->subtotal) }}</td>
                        </tr>

                        <tr>
                            <td class="ps-4 text-muted">Less: Discount</td>
                            <td class="text-end pe-4 text-danger">- ৳{{ number_format($sales->discount, 2) }}</td>
                        </tr>

                        <tr class="table-light fw-semibold">
                            <td class="ps-4">Net Sales</td>
                            <td class="text-end pe-4">{{ $money($netSales) }}</td>
                        </tr>

                        <tr>
                            <td class="ps-4 text-muted">
                                Less: Cost of Goods Sold
                                <div><small>Purchase price of the products sold</small></div>
                            </td>
                            <td class="text-end pe-4 text-danger">- ৳{{ number_format($cogs, 2) }}</td>
                        </tr>

                        <tr class="table-light fw-semibold">
                            <td class="ps-4">Gross Profit</td>
                            <td class="text-end pe-4 {{ $grossProfit < 0 ? 'text-danger' : '' }}">{{ $money($grossProfit) }}</td>
                        </tr>

                        <tr>
                            <td class="ps-4 text-muted">Less: Operating Expenses</td>
                            <td class="text-end pe-4 text-danger">- ৳{{ number_format($totalExpenses, 2) }}</td>
                        </tr>

                        <tr class="fw-bold fs-6 {{ $netProfit < 0 ? 'table-danger' : 'table-success' }}">
                            <td class="ps-4 py-3">{{ $netProfit < 0 ? 'Net Loss' : 'Net Profit' }}</td>
                            <td class="text-end pe-4 py-3">{{ $money($netProfit) }}</td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <p class="text-muted small mt-3 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Purchases in this period were ৳{{ number_format($purchases, 2) }}.
                Purchases are stock bought, not an expense: their cost is counted only when the products are sold (Cost of Goods Sold).
            </p>

        </div>


        {{-- EXPENSES BY CATEGORY --}}
        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Expenses by Category</h6>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Category</th>
                                <th class="text-center">Entries</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($expensesByCategory as $row)
                                <tr>
                                    <td class="ps-4">{{ $row->name }}</td>
                                    <td class="text-center">{{ $row->count }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($row->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No expenses in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if($expensesByCategory->isNotEmpty())
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td class="ps-4" colspan="2">Total</td>
                                    <td class="text-end pe-4">৳{{ number_format($totalExpenses, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
