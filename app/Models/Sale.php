<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_no',
        'customer_id',
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Customer who purchased the products.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * User/Cashier who created the sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Products/items included in this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
