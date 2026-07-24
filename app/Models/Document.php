<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'documentable_type', 'documentable_id', 'file_name', 'file_path', 
        'file_extension', 'file_size', 'document_type', 'description', 
        'uploaded_by'
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getDownloadUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getUploaderNameAttribute()
    {
        return $this->uploader?->name;
    }

    protected $appends = ['download_url', 'uploader_name'];
}
