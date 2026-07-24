<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'full_name',
        'iso_alpha2',
        'phone_code',
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function tradePartners()
    {
        return $this->hasMany(TradePartner::class);
    }
}
