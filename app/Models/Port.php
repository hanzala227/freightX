<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Port extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'country_id',
        'type',
        'city',
        'state',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Generic shipment relations can be added here or in shipment models
}
