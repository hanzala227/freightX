<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearEndClosing extends Model
{
    protected $table = 'year_end_closings';

    protected $fillable = [
        'fiscal_year',
        'closing_date',
        'action',
        'office_id',
        'created_by',
        'summary',
        'entries_created',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'fiscal_year' => 'integer',
        'entries_created' => 'integer',
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
