<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
    ];

    public function tradePartnersAsCurrency1()
    {
        return $this->hasMany(TradePartner::class, 'bank_currency_1_id');
    }

    public function tradePartnersAsCurrency2()
    {
        return $this->hasMany(TradePartner::class, 'bank_currency_2_id');
    }

    public function charges()
    {
        return $this->hasMany(Charge::class);
    }
}
