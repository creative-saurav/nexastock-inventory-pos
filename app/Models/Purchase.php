<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_no',
        'purchase_date',
        'subtotal',
        'discount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_status',
        'status',
        'note',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    /**
     * Auto-generate purchase_no, e.g. PUR-20261005-0001 (resets daily).
     */
    protected static function booted()
    {
        static::creating(function (Purchase $purchase) {
            $purchase->purchase_no ??= generate_document_no('PUR');
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}