<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseInventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouse_inventory_items';

    protected $fillable = [
        'warehouse_id',
        'customer_id',
        'vendor_id',
        'sku',
        'item_name',
        'upc_ean',
        'mpn',
        'hts_code',
        'description',
        'inner_pack',
        'on_hand_qty',
        'available_qty',
        'unit_id',
        'weight_kg',
        'volume_cbm',
        'dimension_length',
        'dimension_width',
        'dimension_height',
        'dimension_unit',
        'color',
        'remark',
        'create_date',
        'status',
        'product_photo',
    ];

    protected $casts = [
        'create_date' => 'date',
    ];

    public function warehouse()
    {
        return $this->belongsTo(TradePartner::class, 'warehouse_id')->whereIn('type', ['WH', 'WAREHOUSE']);
    }

    public function customer()
    {
        return $this->belongsTo(TradePartner::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(TradePartner::class, 'vendor_id');
    }

    public function unit()
    {
        return $this->belongsTo(PackageUnit::class, 'unit_id');
    }

    public function latestReceivingItem()
    {
        return $this->hasOne(WarehouseReceivingItem::class, 'sku_no', 'sku')->latestOfMany();
    }
}
