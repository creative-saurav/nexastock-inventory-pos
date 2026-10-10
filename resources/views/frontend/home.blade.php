@extends('frontend.layouts.app')

@section('content')

@php
    $phoneHref = setting('phone') ? 'tel:' . preg_replace('/[^0-9+]/', '', setting('phone')) : null;
    $tabCategories = $latestProducts->pluck('category')->filter()->unique('id')->take(5);
    $icons = ['bi-cup-straw', 'bi-laptop', 'bi-phone', 'bi-basket', 'bi-house-heart', 'bi-bag', 'bi-lightning-charge', 'bi-gift'];
@endphp


{{-- =========================
     HERO
========================== --}}
<section class="hero-wrap">

    <div class="container">

        <div class="hero">

            <div class="row align-items-center g-5">

                <div class="col-lg-6">

                    <div class="hero-badge">
                        <span>NEW</span>
                        Fresh arrivals added every week
                    </div>

                    <h1>
                        Shop smarter.<br>
                        <span class="gradient-text">Everything you need</span><br>
                        in one store.
                    </h1>

                    <p class="hero-lead">
                        Browse {{ number_format($productCount) }} products from trusted brands, check today's price and availability online, then pick them up at {{ setting('name') }}.
                    </p>

                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('shop.products') }}" class="btn btn-primary btn-lg">
                            Start shopping
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-geo-alt me-1"></i>
                            Find our store
                        </a>

                    </div>

                    <div class="hero-stats">

                        <div class="hero-stat">
                            <strong>{{ number_format($productCount) }}</strong>
                            <span>Products</span>
                        </div>

                        <div class="hero-stat">
                            <strong>{{ number_format($categoryCount) }}</strong>
                            <span>Categories</span>
                        </div>

                        <div class="hero-stat">
                            <strong>{{ number_format($brandCount) }}</strong>
                            <span>Brands</span>
                        </div>

                    </div>

                </div>


                <div class="col-lg-6">

                    @if($showcase->count() >= 3)

                        <div class="showcase">

                            @foreach($showcase as $index => $item)
                                <a
                                    href="{{ route('shop.products.show', $item->slug) }}"
                                    class="showcase-card {{ ['main', 'side-1', 'side-2'][$index] }}"
                                >
                                    <div class="img">
                                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                                    </div>
                                    <div class="meta">
                                        <small>{{ $item->category?->name }}</small>
                                        <div>{{ $item->name }}</div>
                                        <strong>৳{{ number_format($item->selling_price, 0) }}</strong>
                                    </div>
                                </a>
                            @endforeach

                            <span class="showcase-chip chip-1">
                                <i class="bi bi-patch-check-fill"></i>
                                100% genuine products
                            </span>

                            <span class="showcase-chip chip-2">
                                <i class="bi bi-receipt"></i>
                                Invoice with every purchase
                            </span>

                        </div>

                    @else

                        <div class="showcase-empty">
                            <div class="text-center">
                                <i class="bi bi-bag-heart fs-1 d-block mb-2"></i>
                                New products coming soon
                            </div>
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- FEATURE STRIP --}}
        <div class="panel feature-strip">

            <div class="feature-item">
                <span class="icon"><i class="bi bi-patch-check"></i></span>
                <div>
                    <strong>Genuine products</strong>
                    <small>Sourced from trusted suppliers</small>
                </div>
            </div>

            <div class="feature-item">
                <span class="icon"><i class="bi bi-tags"></i></span>
                <div>
                    <strong>Fair, clear prices</strong>
                    <small>The price online is the price in store</small>
                </div>
            </div>

            <div class="feature-item">
                <span class="icon"><i class="bi bi-receipt-cutoff"></i></span>
                <div>
                    <strong>Invoice every time</strong>
                    <small>View and print it from your account</small>
                </div>
            </div>

            <div class="feature-item">
                <span class="icon"><i class="bi bi-headset"></i></span>
                <div>
                    <strong>Friendly support</strong>
                    <small>{{ setting('phone') ?: 'Call or visit us any time' }}</small>
                </div>
            </div>

        </div>

    </div>

</section>



{{-- =========================
     CATEGORIES
========================== --}}
@if($categories->isNotEmpty())

    <section class="section pb-0 reveal">

        <div class="container">

            <div class="section-head">

                <div>
                    <span class="eyebrow">Categories</span>
                    <h2 class="section-title">Shop by category</h2>
                    <p class="section-subtitle">Jump straight to what you need.</p>
                </div>

                <a href="{{ route('shop.products') }}" class="link-arrow">
                    All categories <i class="bi bi-arrow-right"></i>
                </a>

            </div>

            <div class="row g-3 g-lg-4">

                @foreach($categories as $category)

                    <div class="col-6 col-md-4 col-xl-2">

                        <a href="{{ route('shop.products', ['category' => $category->slug]) }}" class="category-tile tone-{{ ($loop->index % 6) + 1 }}">

                            <span class="tile-icon">
                                <i class="bi {{ $icons[$loop->index % count($icons)] }}"></i>
                            </span>

                            <span class="tile-arrow"><i class="bi bi-arrow-right"></i></span>

                            <span class="tile-body">
                                <span class="tile-name d-block">{{ $category->name }}</span>
                                <span class="tile-count">{{ $category->products_count }} {{ \Illuminate\Support\Str::plural('item', $category->products_count) }}</span>
                            </span>

                        </a>

                    </div>

                @endforeach

            </div>

        </div>

    </section>

@endif



{{-- =========================
     NEW ARRIVALS (with category tabs)
========================== --}}
<section class="section reveal">

    <div class="container">

        <div class="section-head">

            <div>
                <span class="eyebrow">New arrivals</span>
                <h2 class="section-title">Just landed in store</h2>
                <p class="section-subtitle">The latest additions to our shelves.</p>
            </div>

            @if($tabCategories->count() > 1)
                <div class="pill-tabs" role="tablist" aria-label="Filter new arrivals by category">
                    <button type="button" class="active" data-filter="all">All</button>
                    @foreach($tabCategories as $tabCategory)
                        <button type="button" data-filter="cat-{{ $tabCategory->id }}">{{ $tabCategory->name }}</button>
                    @endforeach
                </div>
            @endif

        </div>

        @if($latestProducts->isNotEmpty())

            <div class="row g-3 g-lg-4" id="arrivalsGrid">

                @foreach($latestProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3" data-cat="cat-{{ $product->category_id }}">
                        @include('frontend.partials.product-card')
                    </div>
                @endforeach

            </div>

            <div class="text-center mt-5">
                <a href="{{ route('shop.products') }}" class="btn btn-soft btn-lg">
                    View all products
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

        @else

            <div class="panel text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
                <p class="text-muted mt-2 mb-0">Products are coming soon.</p>
            </div>

        @endif

    </div>

</section>



{{-- =========================
     PROMO BANNERS
========================== --}}
<section class="pb-5 reveal">

    <div class="container">

        <div class="row g-4">

            <div class="col-lg-6">

                <div class="promo promo-dark">

                    <span class="eyebrow" style="color: #a5b4fc;">Visit us</span>

                    <h3 class="mb-2">See it, touch it, take it home.</h3>

                    <p class="mb-4" style="max-width: 380px;">
                        {{ setting('address') ?: 'Come to our store to see products in person.' }}
                    </p>

                    <div class="d-flex flex-wrap gap-2">

                        @if(setting('address'))
                            <a
                                href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('address')) }}"
                                target="_blank"
                                rel="noopener"
                                class="btn btn-white"
                            >
                                <i class="bi bi-map me-1"></i>
                                Get directions
                            </a>
                        @endif

                        @if($phoneHref)
                            <a href="{{ $phoneHref }}" class="btn btn-outline-light">
                                <i class="bi bi-telephone me-1"></i>
                                Call store
                            </a>
                        @endif

                    </div>

                    <i class="bi bi-shop promo-icon" aria-hidden="true"></i>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="promo promo-light">

                    <span class="eyebrow">Your account</span>

                    <h3 class="mb-2">Every purchase, saved for you.</h3>

                    <p class="text-muted mb-4" style="max-width: 380px;">
                        Create a free account to see your full purchase history and print any invoice, any time.
                    </p>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-person-circle me-1"></i>
                            Go to my account
                        </a>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                Create free account
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-soft">
                                Sign in
                            </a>
                        </div>
                    @endauth

                    <i class="bi bi-receipt promo-icon" aria-hidden="true"></i>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================
     BEST SELLERS
========================== --}}
@if($bestSellers->isNotEmpty())

    <section class="section pt-4 reveal">

        <div class="container">

            <div class="section-head">

                <div>
                    <span class="eyebrow">Best sellers</span>
                    <h2 class="section-title">Customer favourites</h2>
                    <p class="section-subtitle">What our customers buy the most.</p>
                </div>

                <a href="{{ route('shop.products') }}" class="link-arrow">
                    Shop all <i class="bi bi-arrow-right"></i>
                </a>

            </div>

            <div class="row g-3 g-lg-4">

                @foreach($bestSellers->take(4) as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('frontend.partials.product-card')
                    </div>
                @endforeach

            </div>

        </div>

    </section>

@endif



{{-- =========================
     BRANDS
========================== --}}
@if($brands->isNotEmpty())

    <section class="section pt-4 reveal">

        <div class="container">

            <div class="text-center mb-4">
                <span class="eyebrow">Our brands</span>
                <h2 class="section-title">Trusted brands we carry</h2>
            </div>

            <div class="row g-3 justify-content-center">

                @foreach($brands as $brand)

                    <div class="col-6 col-md-3 col-lg-2">

                        <a href="{{ route('shop.products', ['brand' => $brand->slug]) }}" class="brand-tile" title="{{ $brand->name }}">
                            @if($brand->logo)
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}">
                            @else
                                <span>{{ $brand->name }}</span>
                            @endif
                        </a>

                    </div>

                @endforeach

            </div>

        </div>

    </section>

@endif


<div class="pb-5"></div>

@endsection


@push('scripts')
<script>
    "use strict";

    // New arrivals category tabs (client side, no reload)
    document.querySelectorAll('.pill-tabs button').forEach(function (button) {

        button.addEventListener('click', function () {

            document.querySelectorAll('.pill-tabs button').forEach(function (other) {
                other.classList.toggle('active', other === button);
            });

            const filter = button.dataset.filter;

            document.querySelectorAll('#arrivalsGrid [data-cat]').forEach(function (cell) {
                cell.classList.toggle('d-none', filter !== 'all' && cell.dataset.cat !== filter);
            });
        });
    });
</script>
@endpush
