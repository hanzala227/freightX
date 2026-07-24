<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerDefaultFreight extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'transport_mode', 'section', 'ship_mode',
        'freight_code', 'description', 'pc', 'type', 'unit', 'currency_id',
        'volume', 'rate', 'amount', 'agent_amount'
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
