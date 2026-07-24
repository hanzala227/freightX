<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirExportMemo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'air_export_id', 'subject', 'body', 'user_id'
    ];

    public function airExport() { return $this->belongsTo(AirExport::class); }
    public function user() { return $this->belongsTo(User::class); }
}
