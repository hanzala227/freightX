<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankBatchLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_type',
        'post_date',
        'action_date',
        'office',
        'bank_name',
        'total_amount',
        'payment_count',
        'payment_ids',
    ];

    protected $casts = [
        'post_date' => 'date',
        'action_date' => 'date',
        'total_amount' => 'decimal:2',
        'payment_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
