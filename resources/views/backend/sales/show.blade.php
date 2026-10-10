
@extends('backend.layouts.admin')

@section('page-title', 'Sale Details')
@section('title', 'Sale Details')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Sale Details</h4>
            <p class="text-muted mb-0">
                View complete sale and payment information.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('sales') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Sales
            </a>

            <a href="{{ route('sales.invoice', ['id' => $sale->id, 'print' => 1]) }}"
               target="_blank"
               class="btn btn-primary">
                <i class="bi bi-printer me-1"></i>
                Print Invoice
            </a>
        </div>
    </div>


    <div id="sale-print-area">

    {{-- INVOICE HEADER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="bi bi-receipt fs-3"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Invoice: {{ $sale->invoice_no }}
                            </h5>

                            <span class="text-muted">
                                Created on
                                {{ $sale->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 text-md-end">
                    @if($sale->payment_status === 'paid')
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>
                            Paid
                        </span>
                    @elseif($sale->payment_status === 'partial')
                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                            <i class="bi bi-clock me-1"></i>
                            Partial Payment
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Due
                        </span>
                    @endif
                </div>

            </div>

        </div>
    </div>


    {{-- CUSTOMER AND SALE INFORMATION --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-person me-2 text-primary"></i>
                        Customer Information
                    </h6>
                </div>

                <div class="card-body p-4">

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            Customer Name
                        </small>

                        <span class="fw-semibold">
                            {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                        </span>
                    </div>

                    @if($sale->customer)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">
                                Email Address
                            </small>

                            <span>{{ $sale->customer->email ?? 'N/A' }}</span>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-1">
                                Phone Number
                            </small>

                            <span>{{ $sale->customer->phone ?? 'N/A' }}</span>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            No registered customer was selected for this sale.
                        </p>
                    @endif

                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>
                        Sale Information
                    </h6>
                </div>

                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-5 text-muted">Invoice Number</div>
                        <div class="col-7 fw-semibold">
                            {{ $sale->invoice_no }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5 text-muted">Sold By</div>
                        <div class="col-7 fw-semibold">
                            {{ $sale->user?->name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-5 text-muted">Sale Date</div>
                        <div class="col-7">
                            {{ $sale->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5 text-muted">Payment Method</div>
                        <div class="col-7 fw-semibold">
                            {{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- SOLD PRODUCTS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">
                <i class="bi bi-box-seam me-2 text-primary"></i>
                Sold Products
            </h6>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Product</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($sale->items as $item)
                            <tr>
                                <td class="ps-4">{{ $loop->iteration }}</td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $item->product?->name ?? 'Product Deleted' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="text-end">
                                    ৳{{ number_format($item->price, 2) }}
                                </td>

                                <td class="text-end pe-4 fw-semibold">
                                    ৳{{ number_format($item->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No sale items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>


    {{-- PAYMENT SUMMARY --}}
    <div class="row justify-content-end mb-4">
        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">Payment Summary</h6>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span>৳{{ number_format($sale->subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Discount</span>
                        <span class="text-danger">
                            - ৳{{ number_format($sale->discount, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <span>৳{{ number_format($sale->tax, 2) }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Grand Total</span>
                        <span class="fw-bold fs-5 text-primary">
                            ৳{{ number_format($sale->grand_total, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Paid Amount</span>
                        <span class="text-success fw-semibold">
                            ৳{{ number_format($sale->paid_amount, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Change Returned</span>
                        <span class="fw-semibold">
                            ৳{{ number_format($sale->change_amount, 2) }}
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>


    {{-- FOOTER --}}
    <div class="text-center text-muted py-3">
        <small>Thank you for your business!</small>
    </div>

    </div>

</div>

@endsection
