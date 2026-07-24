<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerMemo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'subject', 'content', 'user_id'
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
