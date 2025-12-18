<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalCost extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'date',
        'category',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
    ];
}
