@extends('backend.layouts.admin')

@section('title', 'Purchase ' . $purchase->purchase_no)
@section('page-title', 'Incoming Stock')

@section('content')

@php
    $qty = fn ($value) => rtrim(rtrim(number_format($value, 2), '0'), '.');
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-semibold mb-1">{{ $purchase->purchase_no }}</h4>
            <p class="text-muted mb-0">
                From {{ $purchase->supplier?->name ?? 'N/A' }} on {{ $purchase->purchase_date->format('d M Y') }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

            @include('backend.staff._purchase_status', ['status' => $purchase->status])

            <a href="{{ route('staff.purchases') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>



    <div class="row g-4">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-box-seam me-2 text-primary"></i>
                        Products Received
                    </h6>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center pe-4">Current Stock</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($purchase->items as $item)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product?->name ?? 'Product Deleted' }}</div>
                                        <small class="text-muted">{{ $item->product?->sku }}</small>
                                    </td>
                                    <td class="text-center fw-semibold">
                                        {{ $qty($item->quantity) }} {{ $item->product?->unit?->short_name }}
                                    </td>
                                    <td class="text-center pe-4 text-muted">
                                        @if($item->product)
                                            {{ $qty($item->product->stock) }} {{ $item->product->unit?->short_name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="ps-4" colspan="2">Total</td>
                                <td class="text-center">{{ $qty($purchase->items->sum('quantity')) }}</td>
                                <td class="pe-4"></td>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>


        <div class="col-xl-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">Supplier</h6>

                    <div class="fw-semibold">{{ $purchase->supplier?->name ?? 'N/A' }}</div>

                    @if($purchase->supplier?->phone)
                        <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $purchase->supplier->phone }}</div>
                    @endif

                    @if($purchase->supplier?->address)
                        <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $purchase->supplier->address }}</div>
                    @endif

                    @if($purchase->note)
                        <hr>
                        <h6 class="fw-bold mb-2">Note</h6>
                        <p class="text-muted small mb-0">{{ $purchase->note }}</p>
                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
