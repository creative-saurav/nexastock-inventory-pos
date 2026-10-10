@extends('frontend.layouts.app')

@section('title', $activeCategory?->name ?? 'Shop')

@section('content')

@php
    // Build a URL that keeps the other filters when one changes
    $filterUrl = fn (array $changes) => route('shop.products', array_filter(
        array_merge(request()->except('page'), $changes),
        fn ($value) => $value !== null && $value !== ''
    ));

    $sorts = ['' => 'Newest first', 'price_low' => 'Price: low to high', 'price_high' => 'Price: high to low', 'name' => 'Name: A to Z'];

    $chips = collect([
        request('search') ? ['label' => '"' . request('search') . '"', 'url' => $filterUrl(['search' => null])] : null,
        $activeCategory ? ['label' => $activeCategory->name, 'url' => $filterUrl(['category' => null])] : null,
        $activeBrand ? ['label' => $activeBrand->name, 'url' => $filterUrl(['brand' => null])] : null,
        request()->boolean('in_stock') ? ['label' => 'In stock', 'url' => $filterUrl(['in_stock' => null])] : null,
    ])->filter();
@endphp


{{-- PAGE HEAD --}}
<section class="page-head">

    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @if($activeCategory)
                    <li class="breadcrumb-item"><a href="{{ route('shop.products') }}">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $activeCategory->name }}</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                @endif
            </ol>
        </nav>

        <h1>{{ $activeCategory?->name ?? 'All products' }}</h1>

        <p class="text-muted mb-0">
            {{ $activeCategory?->description ?: 'Browse our full range. Prices are updated daily.' }}
        </p>

    </div>

</section>



<section class="section pt-4">

    <div class="container">

        <div class="row g-4">

            {{-- FILTERS (inline on desktop, slide-in on mobile) --}}
            <aside class="col-lg-3">

                <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="shopFilters" aria-labelledby="shopFiltersLabel">

                    <div class="offcanvas-header d-lg-none">
                        <h5 class="offcanvas-title fw-bold" id="shopFiltersLabel">Filters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#shopFilters" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body p-3 p-lg-0">

                        <div class="panel filter-panel w-100">

                            <div class="filter-section">

                                <div class="filter-title">Search</div>

                                <form action="{{ route('shop.products') }}" method="GET">

                                    @foreach(request()->only(['category', 'brand', 'sort', 'in_stock']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach

                                    <div class="input-group">
                                        <input type="search" name="search" class="form-control" placeholder="Name or SKU..." value="{{ request('search') }}" aria-label="Search">
                                        <button class="btn btn-primary" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                                    </div>

                                </form>

                            </div>


                            <div class="filter-section">

                                <div class="filter-title">Categories</div>

                                <div class="filter-list">

                                    <a href="{{ $filterUrl(['category' => null]) }}" class="{{ $activeCategory ? '' : 'active' }}">
                                        All categories
                                    </a>

                                    @foreach($categories as $category)
                                        <a href="{{ $filterUrl(['category' => $category->slug]) }}" class="{{ $activeCategory?->id === $category->id ? 'active' : '' }}">
                                            {{ $category->name }}
                                            <span class="count">{{ $category->products_count }}</span>
                                        </a>
                                    @endforeach

                                </div>

                            </div>


                            @if($brands->isNotEmpty())

                                <div class="filter-section">

                                    <div class="filter-title">Brands</div>

                                    <div class="filter-list">

                                        <a href="{{ $filterUrl(['brand' => null]) }}" class="{{ $activeBrand ? '' : 'active' }}">
                                            All brands
                                        </a>

                                        @foreach($brands as $brand)
                                            <a href="{{ $filterUrl(['brand' => $brand->slug]) }}" class="{{ $activeBrand?->id === $brand->id ? 'active' : '' }}">
                                                {{ $brand->name }}
                                            </a>
                                        @endforeach

                                    </div>

                                </div>

                            @endif


                            <div class="filter-section">

                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        id="inStockOnly"
                                        @checked(request()->boolean('in_stock'))
                                        onchange="window.location = this.checked ? '{{ $filterUrl(['in_stock' => 1]) }}' : '{{ $filterUrl(['in_stock' => null]) }}'"
                                    >
                                    <label class="form-check-label fw-medium" for="inStockOnly">Show in-stock items only</label>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </aside>



            {{-- RESULTS --}}
            <div class="col-lg-9">

                <div class="panel toolbar d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <button class="btn btn-soft btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopFilters" aria-controls="shopFilters">
                            <i class="bi bi-sliders me-1"></i>
                            Filters
                        </button>

                        <span class="text-muted">
                            <strong class="text-dark">{{ $products->total() }}</strong> {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
                        </span>

                        @foreach($chips as $chip)
                            <a href="{{ $chip['url'] }}" class="filter-chip" title="Remove filter">
                                {{ $chip['label'] }}
                                <i class="bi bi-x"></i>
                            </a>
                        @endforeach

                        @if($chips->count() > 1)
                            <a href="{{ route('shop.products') }}" class="small text-muted text-decoration-none ms-1">Clear all</a>
                        @endif

                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <label for="sort" class="text-muted small text-nowrap">Sort by</label>

                        <select id="sort" class="form-select form-select-sm" onchange="window.location = this.value" style="min-width: 180px;">
                            @foreach($sorts as $value => $label)
                                <option value="{{ $filterUrl(['sort' => $value]) }}" @selected(request('sort', '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>

                    </div>

                </div>


                @if($products->isNotEmpty())

                    <div class="row g-3 g-lg-4">

                        @foreach($products as $product)
                            <div class="col-6 col-md-4">
                                @include('frontend.partials.product-card')
                            </div>
                        @endforeach

                    </div>

                    @if($products->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $products->links() }}
                        </div>
                    @endif

                @else

                    <div class="panel text-center py-5 px-3">

                        <div class="mx-auto mb-3 rounded-circle bg-primary-soft d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                            <i class="bi bi-search fs-3 text-primary"></i>
                        </div>

                        <h5 class="fw-bold">No products found</h5>

                        <p class="text-muted">Try a different search or remove some filters.</p>

                        <a href="{{ route('shop.products') }}" class="btn btn-primary">
                            Clear all filters
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</section>

@endsection
