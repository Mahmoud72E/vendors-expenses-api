<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $dates = [
        'deleted_at',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
