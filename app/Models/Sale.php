<?php

namespace App\Models;

use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'date',
        'customer',
        'total_amount',
        'description',
    ];

    protected $casts = [
        'total_amount' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
