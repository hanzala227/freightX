<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClearCheckExcelLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_name',
        'bank_name',
        'clear_date',
        'total_amount',
        'matched_count',
        'unmatched_count',
        'matched_ids',
        'unmatched_rows',
    ];

    protected $casts = [
        'clear_date' => 'date',
        'total_amount' => 'decimal:2',
        'matched_ids' => 'array',
        'unmatched_rows' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
