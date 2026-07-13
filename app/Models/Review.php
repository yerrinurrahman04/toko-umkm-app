<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'order_item_id',
        'rating',
        'comment',
        'is_moderated'
    ];

    protected $casts = [
        'is_moderated' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
