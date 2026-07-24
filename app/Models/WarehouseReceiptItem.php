<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'warehouse_receipt_items';

    protected $fillable = [
        'warehouse_receipt_id',
        'length_cm',
        'width_cm',
        'height_cm',
        'dimension',
        'pkg_qty',
        'unit',
        'sku_po',
        'pallet_qty',
        'weight_kg',
        'weight_lbs',
        'volume_cbm',
        'volume_cft',
        'act_weight_kg',
        'act_weight_lbs',
        'item_date',
    ];

    protected $casts = [
        'length_cm' => 'decimal:2',
        'width_cm' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'pkg_qty' => 'decimal:2',
        'pallet_qty' => 'decimal:2',
        'weight_kg' => 'decimal:3',
        'weight_lbs' => 'decimal:3',
        'volume_cbm' => 'decimal:6',
        'volume_cft' => 'decimal:6',
        'act_weight_kg' => 'decimal:3',
        'act_weight_lbs' => 'decimal:3',
        'item_date' => 'date',
    ];

    public function receipt()
    {
        return $this->belongsTo(WarehouseReceipt::class, 'warehouse_receipt_id');
    }
}
