<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'production_date',
        'finished_date',
        'batch_code',
        'quantity',
        'failed_qty',
        'unit',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'failed_qty' => 'integer',
    ];

    public function getGoodQtyAttribute()
    {
        return $this->quantity - $this->failed_qty;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
