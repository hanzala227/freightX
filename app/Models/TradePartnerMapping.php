<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradePartnerMapping extends Model
{
    protected $fillable = [
        'target',
        'status',
        'sender_id',
        'key',
        'init_target_code',
        'trade_partner_id',
        'target_code',
    ];

    public function tradePartner(): BelongsTo
    {
        return $this->belongsTo(TradePartner::class);
    }
}
