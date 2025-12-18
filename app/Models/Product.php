<?php

namespace App\Models;

use App\Models\SaleItem;
use App\Models\Production;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
