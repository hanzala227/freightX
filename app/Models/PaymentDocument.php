<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PaymentDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_documents';

    protected $fillable = [
        'payment_id',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'description',
        'uploaded_by',
    ];

    public function payment()
    {
        return $this->belongsTo(AccountingPayment::class, 'payment_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
