<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OceanImportHblReceipt extends Model
{
    use HasFactory;

    protected $table = 'ocean_import_hbl_receipts';

    protected $fillable = [
        'hbl_id',
        'receipt_no',
        'vin_no',
        'total_pcs',
        'available_pcs',
        'allocated_pcs',
        'unit',
        'actual_weight',
        'measurement',
        'remarks'
    ];

    public function hbl()
    {
        return $this->belongsTo(OceanImportHbl::class, 'hbl_id');
    }
}
