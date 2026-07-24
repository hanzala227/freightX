<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OceanImportHistory extends Model
{
    protected $table = 'ocean_import_history';

    protected $fillable = [
        'ocean_import_id', 'action', 'details', 'user_id'
    ];

    public function oceanImport() { return $this->belongsTo(OceanImport::class); }
    public function user() { return $this->belongsTo(User::class); }
}
