<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function isValid($amount = 0)
    {
        $today = now()->format('Y-m-d');
        $startDate = $this->start_date->format('Y-m-d');
        $endDate = $this->end_date->format('Y-m-d');
        
        return $this->is_active 
            && $startDate <= $today 
            && $endDate >= $today 
            && $amount >= $this->min_spend;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid($amount)) {
            return 0;
        }

        if ($this->type === 'percent') {
            return $amount * ($this->value / 100);
        }

        return min($this->value, $amount);
    }
}
