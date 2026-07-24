<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerContact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'is_representative', 'email_name', 'title', 
        'division', 'cell_phone', 'phone', 'fax', 'email', 'remark'
    ];

    protected $casts = [
        'is_representative' => 'boolean',
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }
}
