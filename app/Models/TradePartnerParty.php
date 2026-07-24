<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerParty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'party_type', 'related_partner_id', 'is_default', 'description'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }

    public function relatedPartner()
    {
        return $this->belongsTo(TradePartner::class, 'related_partner_id');
    }
}
