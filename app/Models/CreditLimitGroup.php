<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLimitGroup extends Model
{
    protected $fillable = [
        'name', 'description',
        'payment_type', 'credit_term_unit',
        'credit_term_days', 'credit_limit',
    ];

    protected $casts = [
        'credit_term_days' => 'integer',
        'credit_limit' => 'decimal:2',
    ];

    public function tradePartners()
    {
        return $this->hasMany(TradePartner::class, 'credit_limit_group_id');
    }
}
