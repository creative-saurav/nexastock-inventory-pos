@extends('backend.layouts.admin')

@section('title', 'Invoice ' . $sale->invoice_no)
@section('page-title', 'Purchase Details')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-semibold mb-1">Invoice {{ $sale->invoice_no }}</h4>
            <p class="text-muted mb-0">
                Purchased on {{ $sale->created_at->format('d M Y, h:i A') }}
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('customer.purchases') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i>
                My Purchases
            </a>

            <a
                href="{{ route('customer.purchases.invoice', ['id' => $sale->id, 'print' => 1]) }}"
                target="_blank"
                class="btn btn-primary"
            >
                <i class="bi bi-printer me-1"></i>
                Print Invoice
            </a>

        </div>

    </div>



    <div class="row g-4">

        {{-- ITEMS --}}
        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-box-seam me-2 text-primary"></i>
                        Items ({{ $sale->items->sum('quantity') }})
                    </h6>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sale->items as $item)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->product?->name ?? 'Product no longer available' }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end pe-4 fw-semibold">৳{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>

        </div>



        {{-- SUMMARY --}}
        <div class="col-xl-4">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">Payment Summary</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>৳{{ number_format($sale->subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Discount</span>
                        <span class="text-success">- ৳{{ number_format($sale->discount, 2) }}</span>
                    </div>

                    @if($sale->tax > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax</span>
                            <span>৳{{ number_format($sale->tax, 2) }}</span>
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Grand Total</span>
                        <span class="fw-bold fs-5 text-primary">৳{{ number_format($sale->grand_total, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Paid ({{ ucwords(str_replace('_', ' ', $sale->payment_method)) }})</span>
                        <span class="fw-semibold">৳{{ number_format($sale->paid_amount, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Change Returned</span>
                        <span>৳{{ number_format($sale->change_amount, 2) }}</span>
                    </div>

                </div>

            </div>


            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">Store</h6>

                    <div class="fw-semibold">{{ setting('name') }}</div>

                    @if(setting('address'))
                        <div class="text-muted small">{{ setting('address') }}</div>
                    @endif

                    @if(setting('phone'))
                        <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ setting('phone') }}</div>
                    @endif

                    <div class="text-muted small mt-2">
                        Served by {{ $sale->user?->name ?? 'our team' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
