<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chargeable_type', 'chargeable_id', 'type', 'charge_code', 
        'charge_name', 'bill_to_id', 'vendor_id', 'pc', 'qty', 'unit', 
        'currency_id', 'rate', 'amount', 'tax_percent', 'tax_amount', 
        'total_amount', 'is_invoiced', 'invoice_no', 'invoice_date', 'remark'
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_invoiced' => 'boolean',
        'invoice_date' => 'date',
    ];

    public function chargeable()
    {
        return $this->morphTo();
    }

    public function billTo() { return $this->belongsTo(TradePartner::class, 'bill_to_id'); }
    public function vendor() { return $this->belongsTo(TradePartner::class, 'vendor_id'); }
    public function currency() { return $this->belongsTo(Currency::class); }
}
