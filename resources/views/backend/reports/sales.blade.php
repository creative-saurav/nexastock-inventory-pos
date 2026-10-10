@extends('backend.layouts.admin')

@section('page-title', 'Sales Report')
@section('title', 'Sales Report')


@section('content')

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Sales Report',
        'subtitle' => 'Invoices and daily sales',
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.sales') }}" method="GET">

                <div class="row g-2 align-items-end">

                    @include('backend.reports._dates')


                    <div class="col-lg-2 col-md-4">

                        <label class="form-label small text-muted mb-1">Sold By</label>

                        <select name="user_id" class="form-select">

                            <option value="">All Users</option>

                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-4">

                        <label class="form-label small text-muted mb-1">Payment</label>

                        <select name="payment_method" class="form-select">

                            <option value="">All Methods</option>

                            @foreach(['cash' => 'Cash', 'card' => 'Card', 'mobile_banking' => 'Mobile Banking'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('payment_method') === $value)>
                                    {{ $label }}
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
            @include('backend.reports._stat', ['label' => 'Invoices', 'value' => number_format($summary->count), 'icon' => 'bi-receipt', 'tone' => 'primary'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Gross Sales', 'value' => '৳' . number_format($summary->subtotal, 2), 'sub' => 'Before discount', 'icon' => 'bi-cash-stack', 'tone' => 'info'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Discount Given', 'value' => '৳' . number_format($summary->discount, 2), 'icon' => 'bi-tag', 'tone' => 'warning'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', [
                'label' => 'Net Sales',
                'value' => '৳' . number_format($summary->grand_total, 2),
                'sub' => 'Avg. ৳' . number_format($summary->count ? $summary->grand_total / $summary->count : 0, 2) . ' per invoice',
                'icon' => 'bi-graph-up-arrow',
                'tone' => 'success',
            ])
        </div>

    </div>



    <div class="row g-3 mb-4">

        {{-- DAILY BREAKDOWN --}}
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Daily Breakdown</h6>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th class="text-center">Invoices</th>
                                <th class="text-end">Gross</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end pe-4">Net Sales</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($daily as $day)
                                <tr>
                                    <td class="ps-4">{{ \Carbon\Carbon::parse($day->day)->format('d M Y, D') }}</td>
                                    <td class="text-center">{{ $day->count }}</td>
                                    <td class="text-end">৳{{ number_format($day->subtotal, 2) }}</td>
                                    <td class="text-end text-danger">৳{{ number_format($day->discount, 2) }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($day->grand_total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No sales in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if($daily->isNotEmpty())
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td class="ps-4">Total</td>
                                    <td class="text-center">{{ $summary->count }}</td>
                                    <td class="text-end">৳{{ number_format($summary->subtotal, 2) }}</td>
                                    <td class="text-end text-danger">৳{{ number_format($summary->discount, 2) }}</td>
                                    <td class="text-end pe-4">৳{{ number_format($summary->grand_total, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif

                    </table>

                </div>

            </div>

        </div>


        {{-- BY PAYMENT METHOD --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">By Payment Method</h6>
                </div>

                <div class="card-body">

                    @forelse($byPayment as $row)

                        @php
                            $share = $summary->grand_total > 0 ? ($row->total / $summary->grand_total) * 100 : 0;
                        @endphp

                        <div class="mb-3">

                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">
                                    {{ ucwords(str_replace('_', ' ', $row->payment_method)) }}
                                    <small class="text-muted">({{ $row->count }})</small>
                                </span>
                                <span class="fw-semibold">৳{{ number_format($row->total, 2) }}</span>
                            </div>

                            <div class="progress" style="height: 6px;" role="progressbar" aria-label="{{ $row->payment_method }} share" aria-valuenow="{{ round($share) }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: {{ $share }}%"></div>
                            </div>

                            <small class="text-muted">{{ number_format($share, 1) }}% of net sales</small>

                        </div>

                    @empty

                        <p class="text-muted text-center mb-0 py-3">No sales in this period.</p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>



    {{-- INVOICES --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Invoices</h6>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Invoice No.</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Sold By</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Gross</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Net</th>
                        <th class="pe-4">Payment</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('sales.show', $sale->id) }}" class="fw-semibold text-decoration-none">
                                    {{ $sale->invoice_no }}
                                </a>
                            </td>
                            <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $sale->user?->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ (int) $sale->items_sum_quantity }}</td>
                            <td class="text-end">৳{{ number_format($sale->subtotal, 2) }}</td>
                            <td class="text-end text-danger">৳{{ number_format($sale->discount, 2) }}</td>
                            <td class="text-end fw-semibold">৳{{ number_format($sale->grand_total, 2) }}</td>
                            <td class="pe-4">{{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No invoices in this period.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        @if($sales->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-print-none">
                {{ $sales->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
