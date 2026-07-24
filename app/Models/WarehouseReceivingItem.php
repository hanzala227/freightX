<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WarehouseReceivingItem extends Model
{
    use HasFactory;

    protected $table = 'warehouse_receiving_items';

    protected $fillable = [
        'warehouse_receiving_id',
        'sku_no',
        'customer_po',
        'description',
        'order_po_no',
        'order_qty',
        'qty',
        'qty_unit',
        'pack',
        'pack_unit',
        'pallet',
        'weight_kg',
        'measure_cbm',
        'inventory',
    ];

    protected $casts = [
        'order_qty' => 'decimal:2',
        'qty' => 'decimal:2',
        'pack' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'measure_cbm' => 'decimal:2',
    ];

    public function receiving()
    {
        return $this->belongsTo(WarehouseReceiving::class, 'warehouse_receiving_id');
    }
}
