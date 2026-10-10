<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'expense_no',
        'expense_category_id',
        'user_id',
        'amount',
        'expense_date',
        'payment_method',
        'reference',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Auto-generate expense_no, e.g. EXP-20261010-0001 (resets daily).
     */
    protected static function booted()
    {
        static::creating(function (Expense $expense) {
            $expense->expense_no ??= generate_document_no('EXP');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * User who recorded the expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
