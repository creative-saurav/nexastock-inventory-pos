{{-- Product card. Expects $product (with category and unit loaded) --}}

<article class="product-card">

    <a href="{{ route('shop.products.show', $product->slug) }}" class="product-thumb" tabindex="-1" aria-hidden="true">

        @if($product->image)
            <img src="{{ asset($product->image) }}" alt="" loading="lazy">
        @else
            <span class="placeholder-icon"><i class="bi bi-image"></i></span>
        @endif

        <span class="product-badges">

            @if($product->created_at && $product->created_at->gt(now()->subDays(14)))
                <span class="badge-new">NEW</span>
            @else
                <span></span>
            @endif

            @if($product->stock <= 0)
                <span class="badge bg-dark">Sold out</span>
            @endif

        </span>

        <span class="product-quick">
            <span class="btn btn-sm">
                <i class="bi bi-eye me-1"></i>
                View details
            </span>
        </span>

    </a>

    <div class="product-body">

        <span class="product-category">{{ $product->category?->name }}</span>

        <h3 class="product-name">
            <a href="{{ route('shop.products.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        <div class="product-foot">

            <div class="product-price">
                ৳{{ number_format($product->selling_price, 0) }}
                @if($product->unit)
                    <small>/{{ $product->unit->short_name }}</small>
                @endif
            </div>

            @include('frontend.partials.stock-badge', ['product' => $product, 'compact' => true])

        </div>

    </div>

</article>
