@extends('backend.layouts.admin')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')

@php
    $qty = fn ($value) => rtrim(rtrim(number_format($value, 2), '0'), '.');
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="fw-semibold mb-1">
                Welcome, {{ auth()->user()->name }}
            </h4>

            <p class="text-muted mb-0">
                Stock overview for {{ now()->format('l, d F Y') }}.
            </p>
        </div>

        <a href="{{ route('staff.stock') }}" class="btn btn-primary">
            <i class="bi bi-boxes me-1"></i>
            View Stock List
        </a>

    </div>



    {{-- KEY NUMBERS --}}
    <div class="row g-3 mb-4">

        @php
            $cards = [
                ['label' => 'Active Products', 'value' => number_format($totalProducts), 'icon' => 'bi-box-seam', 'tone' => 'primary', 'link' => route('staff.stock')],
                ['label' => 'Units in Stock', 'value' => $qty($totalUnits), 'icon' => 'bi-boxes', 'tone' => 'info', 'link' => route('staff.stock')],
                ['label' => 'Low Stock', 'value' => $lowStockCount, 'icon' => 'bi-exclamation-triangle', 'tone' => 'warning', 'link' => route('staff.stock', ['stock' => 'low'])],
                ['label' => 'Out of Stock', 'value' => $outOfStockCount, 'icon' => 'bi-x-octagon', 'tone' => 'danger', 'link' => route('staff.stock', ['stock' => 'out'])],
            ];
        @endphp

        @foreach($cards as $card)

            <div class="col-md-6 col-xl-3">

                <a href="{{ $card['link'] }}" class="card border-0 shadow-sm h-100 text-decoration-none text-reset">

                    <div class="card-body d-flex align-items-center gap-3">

                        <span class="badge bg-{{ $card['tone'] }}-subtle text-{{ $card['tone'] }} rounded-3 p-3">
                            <i class="bi {{ $card['icon'] }} fs-5"></i>
                        </span>

                        <div>
                            <small class="text-muted d-block">{{ $card['label'] }}</small>
                            <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                        </div>

                    </div>

                </a>

            </div>

        @endforeach

    </div>



    <div class="row g-3">

        {{-- NEEDS RESTOCK --}}
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="fw-bold mb-0">Needs Restock</h6>
                        <small class="text-muted">Stock at or below the alert quantity</small>
                    </div>

                    @if($lowStockCount + $outOfStockCount > 0)
                        <a href="{{ route('staff.stock', ['stock' => 'low']) }}" class="btn btn-sm btn-light border">View All</a>
                    @endif

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
                                                {{ $qty($product->stock) }} {{ $product->unit?->short_name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 text-muted">
                                        {{ $qty($product->alert_quantity) }} {{ $product->unit?->short_name }}
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


        {{-- RECENT INCOMING STOCK --}}
        <div class="col-xl-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="fw-bold mb-0">Recent Incoming Stock</h6>
                        <small class="text-muted">Latest purchases from suppliers</small>
                    </div>

                    <a href="{{ route('staff.purchases') }}" class="btn btn-sm btn-light border">View All</a>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Purchase</th>
                                <th>Supplier</th>
                                <th class="text-center">Qty</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($recentPurchases as $purchase)
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('staff.purchases.show', $purchase->id) }}" class="fw-semibold text-decoration-none">
                                            {{ $purchase->purchase_no }}
                                        </a>
                                        <div><small class="text-muted">{{ $purchase->purchase_date->format('d M Y') }}</small></div>
                                    </td>
                                    <td>{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ $qty($purchase->items_sum_quantity ?? 0) }}
                                        <div><small class="text-muted">{{ $purchase->items_count }} product(s)</small></div>
                                    </td>
                                    <td class="pe-4">
                                        @include('backend.staff._purchase_status', ['status' => $purchase->status])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No purchases yet.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
