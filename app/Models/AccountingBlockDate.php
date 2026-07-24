<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingBlockDate extends Model
{
    protected $table = 'accounting_block_dates';

    protected $fillable = [
        'office_id',
        'block_date',
        'action',
        'created_by',
    ];

    protected $casts = [
        'block_date' => 'date',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
