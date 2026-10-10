{{-- Availability only; exact stock numbers are not shown to the public --}}

@if($product->stock <= 0)
    <span class="stock-dot stock-out">Out of stock</span>
@elseif($product->stock <= $product->alert_quantity)
    <span class="stock-dot stock-low">{{ ($compact ?? false) ? 'Few left' : 'Only a few left' }}</span>
@else
    <span class="stock-dot stock-in">In stock</span>
@endif
