<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePartnerFilingSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trade_partner_id', 'isf_submission_name', 'isf_submission_state', 
        'isf_zip_code', 'importer_code', 'importer_no'
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class);
    }
}
