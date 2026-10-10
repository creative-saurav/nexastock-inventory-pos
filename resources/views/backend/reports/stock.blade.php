@extends('backend.layouts.admin')

@section('page-title', 'Stock Report')
@section('title', 'Stock Report')


@section('content')

@php
    $qty = fn ($value) => rtrim(rtrim(number_format($value, 2), '0'), '.');
@endphp

<div class="container-fluid" id="report-print-area">

    @include('backend.reports._header', [
        'title' => 'Stock Report',
        'subtitle' => 'Current stock as of ' . now()->format('d M Y, h:i A'),
    ])


    {{-- FILTERS --}}
    <div class="card border-0 shadow-sm mb-4 d-print-none">

        <div class="card-body">

            <form action="{{ route('reports.stock') }}" method="GET">

                <div class="row g-2 align-items-end">

                    <div class="col-lg-4 col-md-6">

                        <label class="form-label small text-muted mb-1">Search</label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Product name or SKU..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-lg-3 col-md-6">

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


                    <div class="col-lg-3 col-md-6">

                        <label class="form-label small text-muted mb-1">Stock Level</label>

                        <select name="stock" class="form-select">

                            <option value="">All Products</option>
                            <option value="low" @selected(request('stock') === 'low')>Low Stock</option>
                            <option value="out" @selected(request('stock') === 'out')>Out of Stock</option>

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">
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
            @include('backend.reports._stat', ['label' => 'Products', 'value' => number_format($summary->count), 'icon' => 'bi-box-seam', 'tone' => 'primary'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Units in Stock', 'value' => $qty($summary->units), 'icon' => 'bi-boxes', 'tone' => 'info'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', ['label' => 'Stock Value (Cost)', 'value' => '৳' . number_format($summary->cost_value, 2), 'sub' => 'At purchase price', 'icon' => 'bi-bag', 'tone' => 'warning'])
        </div>

        <div class="col-md-6 col-xl-3">
            @include('backend.reports._stat', [
                'label' => 'Stock Value (Retail)',
                'value' => '৳' . number_format($summary->sale_value, 2),
                'sub' => 'Potential profit ৳' . number_format($summary->sale_value - $summary->cost_value, 2),
                'icon' => 'bi-cash-stack',
                'tone' => 'success',
            ])
        </div>

    </div>



    <div class="card border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Category</th>
                        <th class="text-center">In Stock</th>
                        <th class="text-end">Purchase Price</th>
                        <th class="text-end">Selling Price</th>
                        <th class="text-end">Stock Value (Cost)</th>
                        <th class="text-end">Stock Value (Retail)</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->sku }}</small>
                            </td>
                            <td>{{ $product->category?->name ?? 'N/A' }}</td>
                            <td class="text-center fw-semibold">
                                {{ $qty($product->stock) }} {{ $product->unit?->short_name }}
                            </td>
                            <td class="text-end">৳{{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-end">৳{{ number_format($product->selling_price, 2) }}</td>
                            <td class="text-end">৳{{ number_format($product->stock * $product->purchase_price, 2) }}</td>
                            <td class="text-end fw-semibold">৳{{ number_format($product->stock * $product->selling_price, 2) }}</td>
                            <td class="pe-4">
                                @if($product->stock <= 0)
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-x-circle me-1"></i>Out of Stock
                                    </span>
                                @elseif($product->stock <= $product->alert_quantity)
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>In Stock
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        @if($products->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-print-none">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
