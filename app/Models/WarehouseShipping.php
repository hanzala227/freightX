<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseShipping extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouse_shippings';

    protected $fillable = [
        'shipping_no',
        'shipping_date',
        'office_id',
        'warehouse_id',
        'customer_id',
        'bill_to_id',
        'ship_to',
        'trucker_id',
        'op_id',
        'quotation_no',
        'quotation_id',
        'order_no',
        'truck_bl_no',
        'order_date',
        'out_date',
        'pallet',
        'status',
        'color',
        'internal_remark',
        'memos_data',
        'items_data',
    ];

    protected $casts = [
        'shipping_date' => 'date',
        'order_date' => 'date',
        'out_date' => 'date',
        'memos_data' => 'array',
        'items_data' => 'array',
    ];

    public function warehouse()
    {
        return $this->belongsTo(TradePartner::class, 'warehouse_id');
    }

    public function customer()
    {
        return $this->belongsTo(TradePartner::class, 'customer_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function billTo()
    {
        return $this->belongsTo(TradePartner::class, 'bill_to_id');
    }

    public function shipTo()
    {
        return $this->belongsTo(TradePartner::class, 'ship_to');
    }

    public function trucker()
    {
        return $this->belongsTo(TradePartner::class, 'trucker_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'op_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
