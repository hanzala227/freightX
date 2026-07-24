<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OceanImportDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ocean_import_id', 'ocean_import_hbl_id', 'file_name', 'file_path', 
        'file_extension', 'file_size', 'description', 'uploaded_by'
    ];

    public function oceanImport() { return $this->belongsTo(OceanImport::class); }
    public function oceanImportHbl() { return $this->belongsTo(OceanImportHbl::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
