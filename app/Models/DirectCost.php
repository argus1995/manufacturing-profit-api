<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectCost extends Model
{
    protected $fillable = [
        'production_id',
        'name',
        'amount',
        'date',
        'note',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
