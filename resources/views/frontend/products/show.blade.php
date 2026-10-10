@extends('frontend.layouts.app')

@section('title', $product->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?? $product->name), 155))

@section('content')

@php
    $phoneHref = setting('phone') ? 'tel:' . preg_replace('/[^0-9+]/', '', setting('phone')) : null;
@endphp

<section class="page-head pb-0">

    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.products') }}">Shop</a></li>
                @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('shop.products', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

    </div>

</section>



<section class="section pt-3">

    <div class="container">

        <div class="row g-4 g-lg-5">

            {{-- IMAGE --}}
            <div class="col-lg-6">

                <div class="gallery">

                    <div class="gallery-main" id="galleryMain">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" id="galleryImage">
                        @else
                            <span class="placeholder-icon"><i class="bi bi-image"></i></span>
                        @endif
                    </div>

                </div>

            </div>


            {{-- DETAILS --}}
            <div class="col-lg-6">

                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">

                    @if($product->category)
                        <a href="{{ route('shop.products', ['category' => $product->category->slug]) }}" class="badge rounded-pill bg-primary-soft text-primary text-decoration-none px-3 py-2">
                            {{ $product->category->name }}
                        </a>
                    @endif

                    @if($product->brand)
                        <a href="{{ route('shop.products', ['brand' => $product->brand->slug]) }}" class="badge rounded-pill bg-light text-dark border text-decoration-none px-3 py-2">
                            {{ $product->brand->name }}
                        </a>
                    @endif

                </div>

                <h1 class="detail-title mb-3">{{ $product->name }}</h1>

                @if($product->description)
                    <p class="text-muted mb-4">{{ \Illuminate\Support\Str::limit($product->description, 220) }}</p>
                @endif


                <div class="price-block mb-4">

                    <div>
                        <small class="text-muted d-block mb-1">Price</small>
                        <div class="detail-price">
                            ৳{{ number_format($product->selling_price, 2) }}
                        </div>
                        @if($product->unit)
                            <small class="text-muted">per {{ strtolower($product->unit->name) }}</small>
                        @endif
                    </div>

                    <div class="ms-auto text-end">
                        <small class="text-muted d-block mb-1">Availability</small>
                        @include('frontend.partials.stock-badge', ['product' => $product])
                    </div>

                </div>


                <div class="spec-tiles mb-4">

                    <div class="spec-tile">
                        <small>SKU</small>
                        <strong>{{ $product->sku }}</strong>
                    </div>

                    <div class="spec-tile">
                        <small>Brand</small>
                        @if($product->brand)
                            <a href="{{ route('shop.products', ['brand' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>
                        @else
                            <strong>—</strong>
                        @endif
                    </div>

                    <div class="spec-tile">
                        <small>Category</small>
                        <strong>{{ $product->category?->name ?? '—' }}</strong>
                    </div>

                    <div class="spec-tile">
                        <small>Sold per</small>
                        <strong>{{ $product->unit?->name ?? '—' }}</strong>
                    </div>

                </div>


                <div class="panel p-4 mb-4">

                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-shop me-1 text-primary"></i>
                        Available in our store
                    </h6>

                    <p class="text-muted small mb-3">
                        We don't take online orders yet. Call us to check stock or reserve this item, then pick it up in store.
                    </p>

                    <div class="d-flex flex-wrap gap-2">

                        @if($phoneHref)
                            <a href="{{ $phoneHref }}" class="btn btn-primary btn-lg flex-fill">
                                <i class="bi bi-telephone me-1"></i>
                                Call to reserve
                            </a>
                        @endif

                        <a href="{{ route('contact') }}" class="btn btn-soft btn-lg flex-fill">
                            <i class="bi bi-geo-alt me-1"></i>
                            Store location
                        </a>

                    </div>

                </div>


                <div class="assurance">
                    <div><i class="bi bi-patch-check"></i>100% genuine</div>
                    <div><i class="bi bi-receipt"></i>Invoice provided</div>
                    <div><i class="bi bi-headset"></i>Friendly support</div>
                </div>

            </div>

        </div>



        {{-- TABS --}}
        <div class="panel mt-5 px-4 px-lg-5 pb-4">

            <ul class="nav detail-tabs border-bottom" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-description" type="button" role="tab" aria-controls="tab-description" aria-selected="true">
                        Description
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-specs" type="button" role="tab" aria-controls="tab-specs" aria-selected="false">
                        Specifications
                    </button>
                </li>

            </ul>

            <div class="tab-content pt-4">

                <div class="tab-pane fade show active" id="tab-description" role="tabpanel">
                    @if($product->description)
                        <p class="mb-0" style="white-space: pre-line; line-height: 1.75;">{{ $product->description }}</p>
                    @else
                        <p class="text-muted mb-0">No description has been added for this product yet.</p>
                    @endif
                </div>

                <div class="tab-pane fade" id="tab-specs" role="tabpanel">

                    <table class="table spec-table mb-0">
                        <tbody>
                            <tr><td>Product name</td><td class="fw-semibold">{{ $product->name }}</td></tr>
                            <tr><td>SKU</td><td class="fw-semibold">{{ $product->sku }}</td></tr>
                            @if($product->barcode)
                                <tr><td>Barcode</td><td class="fw-semibold">{{ $product->barcode }}</td></tr>
                            @endif
                            <tr><td>Brand</td><td class="fw-semibold">{{ $product->brand?->name ?? '—' }}</td></tr>
                            <tr><td>Category</td><td class="fw-semibold">{{ $product->category?->name ?? '—' }}</td></tr>
                            <tr><td>Unit</td><td class="fw-semibold">{{ $product->unit?->name ?? '—' }}</td></tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>



        {{-- RELATED --}}
        @if($related->isNotEmpty())

            <div class="mt-5 pt-lg-3">

                <div class="section-head">
                    <div>
                        <span class="eyebrow">More like this</span>
                        <h2 class="section-title">You may also like</h2>
                    </div>
                    @if($product->category)
                        <a href="{{ route('shop.products', ['category' => $product->category->slug]) }}" class="link-arrow">
                            More in {{ $product->category->name }} <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>

                <div class="row g-3 g-lg-4">

                    @foreach($related as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            @include('frontend.partials.product-card')
                        </div>
                    @endforeach

                </div>

            </div>

        @endif

    </div>

</section>

<div class="pb-5"></div>

@endsection


@push('scripts')
<script>
    "use strict";

    // Zoom the product image where the mouse points
    (function () {

        const frame = document.getElementById('galleryMain');
        const image = document.getElementById('galleryImage');

        if (! frame || ! image || window.matchMedia('(hover: none)').matches) {
            return;
        }

        frame.addEventListener('mousemove', function (e) {
            const rect = frame.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            image.style.transformOrigin = x + '% ' + y + '%';
            image.style.transform = 'scale(1.8)';
        });

        frame.addEventListener('mouseleave', function () {
            image.style.transform = '';
        });

    })();
</script>
@endpush
