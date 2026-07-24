<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRate extends Model
{
    protected $guarded = [];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }
}
