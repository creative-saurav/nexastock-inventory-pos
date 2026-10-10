@extends('backend.layouts.admin')

@section('title', 'Stock List')
@section('page-title', 'Stock List')

@section('content')

@php
    $qty = fn ($value) => rtrim(rtrim(number_format($value, 2), '0'), '.');
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">

        <h4 class="mb-1 fw-semibold">
            Stock List
        </h4>

        <p class="text-muted mb-0">
            Current stock of all active products.
        </p>

    </div>



    <div class="card border-0 shadow-sm">

        {{-- FILTERS --}}
        <div class="p-4 border-bottom">

            <form action="{{ route('staff.stock') }}" method="GET">

                <div class="row g-2 align-items-end">

                    <div class="col-lg-5 col-md-12">

                        <label class="form-label small text-muted mb-1">Search</label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Product name, SKU or barcode..."
                            value="{{ request('search') }}"
                            autofocus
                        >

                    </div>


                    <div class="col-lg-3 col-md-5">

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

                        <label class="form-label small text-muted mb-1">Stock Level</label>

                        <select name="stock" class="form-select">
                            <option value="">All</option>
                            <option value="in" @selected(request('stock') === 'in')>In Stock</option>
                            <option value="low" @selected(request('stock') === 'low')>Low Stock</option>
                            <option value="out" @selected(request('stock') === 'out')>Out of Stock</option>
                        </select>

                    </div>


                    <div class="col-lg-2 col-md-3 d-flex gap-1">

                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        @if(request()->filled('search') || request()->filled('category_id') || request()->filled('stock'))
                            <a href="{{ route('staff.stock') }}" class="btn btn-light border" title="Clear filters">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif

                    </div>

                </div>

            </form>

        </div>



        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th class="text-center">In Stock</th>
                        <th class="text-center">Alert At</th>
                        <th class="text-end">Selling Price</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">

                                    @if($product->image)
                                        <img
                                            src="{{ asset($product->image) }}"
                                            alt=""
                                            class="rounded border"
                                            style="width: 38px; height: 38px; object-fit: cover;"
                                        >
                                    @else
                                        <span class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 38px; height: 38px;">
                                            <i class="bi bi-image"></i>
                                        </span>
                                    @endif

                                    <div>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <small class="text-muted">
                                            {{ $product->sku }}
                                            @if($product->barcode)
                                                &middot; {{ $product->barcode }}
                                            @endif
                                        </small>
                                    </div>

                                </div>
                            </td>
                            <td>{{ $product->category?->name ?? 'N/A' }}</td>
                            <td>{{ $product->brand?->name ?? 'N/A' }}</td>
                            <td class="text-center fw-bold">
                                {{ $qty($product->stock) }} {{ $product->unit?->short_name }}
                            </td>
                            <td class="text-center text-muted">
                                {{ $qty($product->alert_quantity) }}
                            </td>
                            <td class="text-end">৳{{ number_format($product->selling_price, 2) }}</td>
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
                            <td colspan="7" class="text-center py-5">

                                <div class="mb-2">
                                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                                </div>

                                <h6 class="fw-semibold">No Products Found</h6>

                                <p class="text-muted mb-0">No product matched your filters.</p>

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>


        @if($products->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
