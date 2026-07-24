<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    protected $fillable = [
        'journal_entry_id',
        'line_no',
        'gl_account_id',
        'sub',
        'entity_type',
        'trade_partner_id',
        'description',
        'office_id',
        'local_debit',
        'local_credit',
        'currency_id',
        'foreign_rate',
        'foreign_debit',
        'foreign_credit',
    ];

    protected $casts = [
        'local_debit'     => 'decimal:2',
        'local_credit'    => 'decimal:2',
        'foreign_rate'    => 'decimal:6',
        'foreign_debit'   => 'decimal:2',
        'foreign_credit'  => 'decimal:2',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(AccountingJournal::class, 'journal_entry_id');
    }

    public function glAccount()
    {
        return $this->belongsTo(GlAccount::class, 'gl_account_id');
    }

    public function tradePartner()
    {
        return $this->belongsTo(TradePartner::class, 'trade_partner_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
