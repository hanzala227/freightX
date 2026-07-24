<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerCommodity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'description', 'package_unit_id', 'hts_code',
        'pcs', 'net_weight', 'net_weight_unit', 'gross_weight', 
        'gross_weight_unit', 'measurement', 'measurement_unit', 
        'unit_price', 'amount', 'details'
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }

    public function packageUnit()
    {
        return $this->belongsTo(PackageUnit::class);
    }
}
