<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'payment_no',
        'payment_date',
        'trade_partner_id',
        'currency_id',
        'amount',
        'payment_method',
        'reference_no',
        'remark',
        'type',
        'invoice_id',
        'payment_level',
        'show_party_on_check',
        'check_no',
        'clear_date',
        'void_date',
        'office_id',
        'bank_name',
        'bank_currency_id',
        'color',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'clear_date' => 'date',
        'void_date' => 'date',
        'show_party_on_check' => 'boolean',
    ];

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class, 'trade_partner_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function documents()
    {
        return $this->hasMany(PaymentDocument::class, 'payment_id');
    }

    public function memos()
    {
        return $this->hasMany(PaymentMemo::class, 'payment_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function bankCurrency()
    {
        return $this->belongsTo(Currency::class, 'bank_currency_id');
    }
}
