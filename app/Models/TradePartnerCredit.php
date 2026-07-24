<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradePartnerCredit extends Model
{
    protected $fillable = ['trade_partner_id', 'credit_limit', 'available_credit', 'is_blocked'];
}
