<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingBlockHistory extends Model
{
    protected $table = 'accounting_block_history';

    protected $fillable = [
        'program',
        'is_blocked',
        'block_type',
        'ref_no',
        'block_date',
        'office_id',
        'execute_by',
        'executed_at',
        'record_id',
        'record_table',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'block_date' => 'date',
        'executed_at' => 'datetime',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'execute_by');
    }
}
