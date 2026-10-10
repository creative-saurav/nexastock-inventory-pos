@extends('backend.layouts.admin')

@section('page-title', 'Purchase Report')
@section('title', 'Purchase Report')


@section('content')

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Purchase Report',
        'subtitle' => 'Stock purchases and supplier dues (cancelled purchases excluded)',
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.purchases') }}" method="GET">

                <div class="row g-2 align-items-end">

                    @include('backend.reports._dates')


                    <div class="col-lg-2 col-md-4">

                        <label class="form-label small text-muted mb-1">Supplier</label>

                        <select name="supplier_id" class="form-select">

                            <option value="">All Suppliers</option>

                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-1 col-md-4">

                        <label class="form-label small text-muted mb-1">Payment</label>

                        <select name="payment_status" class="form-select">

                            <option value="">All</option>

                            @foreach(['paid' => 'Paid', 'partial' => 'Partial', 'due' => 'Due'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('payment_status') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-1 col-md-4">
                        <button type="submit" class="btn btn-primary w-100" title="Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- SUMMARY --}}
    <div class="row g-3 mb-4 report-stats">

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Purchases', 'value' => number_format($summary->count), 'icon' => 'bi-bag-plus', 'tone' => 'primary'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Total Amount', 'value' => '৳' . number_format($summary->grand_total, 2), 'icon' => 'bi-cash-stack', 'tone' => 'info'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Paid', 'value' => '৳' . number_format($summary->paid_amount, 2), 'icon' => 'bi-check-circle', 'tone' => 'success'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Due to Suppliers', 'value' => '৳' . number_format($summary->due_amount, 2), 'icon' => 'bi-exclamation-circle', 'tone' => 'danger'])
        </div>

    </div>



    <div class="row g-3">

        {{-- BY SUPPLIER --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">By Supplier</h6>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Supplier</th>
                                <th class="text-end">Total</th>
                                <th class="text-end pe-4">Due</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bySupplier as $row)
                                <tr>
                                    <td class="ps-4">
                                        {{ $row->name }}
                                        <div><small class="text-muted">{{ $row->count }} purchase(s)</small></div>
                                    </td>
                                    <td class="text-end fw-semibold">৳{{ number_format($row->total, 2) }}</td>
                                    <td class="text-end pe-4 {{ $row->due > 0 ? 'text-danger' : 'text-muted' }}">৳{{ number_format($row->due, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No purchases in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- PURCHASE LIST --}}
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Purchases</h6>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Purchase No.</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Due</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $purchase->purchase_no }}</td>
                                    <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                                    <td>{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                                    <td class="text-end">৳{{ number_format($purchase->grand_total, 2) }}</td>
                                    <td class="text-end text-success">৳{{ number_format($purchase->paid_amount, 2) }}</td>
                                    <td class="text-end {{ $purchase->due_amount > 0 ? 'text-danger' : 'text-muted' }}">৳{{ number_format($purchase->due_amount, 2) }}</td>
                                    <td class="pe-4">
                                        @if($purchase->payment_status === 'paid')
                                            <span class="badge bg-success-subtle text-success">Paid</span>
                                        @elseif($purchase->payment_status === 'partial')
                                            <span class="badge bg-warning-subtle text-warning">Partial</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Due</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No purchases in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

                @if($purchases->hasPages())
                    <div class="card-footer bg-white border-0 py-3 d-print-none">
                        {{ $purchases->links() }}
                    </div>
                @endif

            </div>

        </div>

    </div>

</div>

@endsection
