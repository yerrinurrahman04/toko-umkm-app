<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'date',
        'total_sales',
        'total_orders'
    ];

    protected $casts = [
        'date' => 'date',
        'total_sales' => 'decimal:2',
        'total_orders' => 'integer'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
