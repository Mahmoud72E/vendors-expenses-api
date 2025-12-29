<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'expense_date',
        'vendor_id',
        'expense_category_id',
        'is_recurring',
        'created_by',
    ];
    protected $casts = [
        'expense_date' => 'date',
        'is_recurring' => 'boolean',
    ];
    protected $dates = [
        'deleted_at',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
