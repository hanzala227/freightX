<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReceiving extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouse_receivings';

    protected $fillable = [
        'warehouse_receipt_id',
        'office_id',
        'customer_id',
        'bill_to_id',
        'ship_from_id',
        'quotation_no',
        'bl_no',
        'trucker_id',
        'container_no',
        'receiving_date',
        'post_date',
        'order_date',
        'expect_date',
        'expiration_date',
        'status',
        'color',
        'pallet',
        'operator_id',
        'internal_remark',
        'memos_data',
    ];

    protected $casts = [
        'receiving_date' => 'date',
        'post_date' => 'date',
        'order_date' => 'date',
        'expect_date' => 'date',
        'expiration_date' => 'date',
        'memos_data' => 'array',
    ];

    public function receipt()
    {
        return $this->belongsTo(WarehouseReceipt::class, 'warehouse_receipt_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function customer()
    {
        return $this->belongsTo(TradePartner::class, 'customer_id');
    }

    public function billTo()
    {
        return $this->belongsTo(TradePartner::class, 'bill_to_id');
    }

    public function shipFrom()
    {
        return $this->belongsTo(TradePartner::class, 'ship_from_id');
    }

    public function trucker()
    {
        return $this->belongsTo(TradePartner::class, 'trucker_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\WarehouseReceivingItem::class, 'warehouse_receiving_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
