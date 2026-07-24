<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruckShipmentContainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'truck_shipment_id', 'container_no', 'tp_sz', 'container_type_id', 'seal_no', 'pickup_no',
        'pkg', 'weight', 'measurement',
        'lfd', 'appointment', 'pickup_date', 'empty_return_date',
        'pier_pass', 'po_no'
    ];

    protected $casts = [
        'pkg' => 'decimal:2',
        'weight' => 'decimal:3',
        'measurement' => 'decimal:3',
        'lfd' => 'date',
        'appointment' => 'date',
        'pickup_date' => 'date',
        'empty_return_date' => 'date',
    ];

    public function truckShipment()
    {
        return $this->belongsTo(TruckShipment::class);
    }

    public function containerType()
    {
        return $this->belongsTo(ContainerType::class);
    }
}
