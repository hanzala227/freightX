<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruckShipmentMemo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'truck_shipment_id', 'subject', 'content', 'user_id'
    ];

    public function truckShipment()
    {
        return $this->belongsTo(TruckShipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
