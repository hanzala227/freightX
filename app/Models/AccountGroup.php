<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function tradePartners()
    {
        return $this->hasMany(TradePartner::class, 'account_group_id');
    }
}
