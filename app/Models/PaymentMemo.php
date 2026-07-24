<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMemo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_memos';

    protected $fillable = [
        'payment_id',
        'subject',
        'content',
        'user_id',
    ];

    public function payment()
    {
        return $this->belongsTo(AccountingPayment::class, 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
