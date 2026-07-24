<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OceanImportMemo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ocean_import_id', 'ocean_import_hbl_id', 'subject', 'content', 'user_id'
    ];

    public function oceanImport() { return $this->belongsTo(OceanImport::class); }
    public function oceanImportHbl() { return $this->belongsTo(OceanImportHbl::class); }
    public function user() { return $this->belongsTo(User::class); }
}
