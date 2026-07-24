<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $table = 'work_orders';

    protected $fillable = [
        'work_order_no',
        'workable_type',
        'workable_id',
        'vendor_id',
        'issue_date',
        'due_date',
        'subject',
        'instructions',
        'status',
        'created_by',
        
        'carrier_id',
        'carrier_bkg_no',
        'place_of_receipt',
        'vessel_info',
        'etd',
        
        'empty_pickup_location_id',
        'empty_pickup_address',
        'empty_pickup_ref',
        'empty_pickup_date',
        
        'freight_pickup_location_id',
        'freight_pickup_address',
        'freight_pickup_ref',
        'freight_pickup_date',
        
        'total_packages',
        'package_unit',
        'container_qty',
        'gross_weight_kgs',
        'gross_weight_lbs',
        
        'show_bill_to',
        'bill_to_id',
        'bill_to_address',
        'bill_to_ref',
        
        'do_not_break_down_pallet',
        'extra_data',
    ];

    protected $casts = [
        'issue_date'   => 'date',
        'due_date'     => 'date',
        'show_bill_to' => 'boolean',
        'do_not_break_down_pallet' => 'boolean',
        'extra_data'   => 'array',
    ];

    public function workable()
    {
        return $this->morphTo();
    }

    public function vendor()
    {
        return $this->belongsTo(TradePartner::class, 'vendor_id');
    }

    public function carrier()
    {
        return $this->belongsTo(TradePartner::class, 'carrier_id');
    }

    public function emptyPickupLocation()
    {
        return $this->belongsTo(TradePartner::class, 'empty_pickup_location_id');
    }

    public function freightPickupLocation()
    {
        return $this->belongsTo(TradePartner::class, 'freight_pickup_location_id');
    }

    public function billTo()
    {
        return $this->belongsTo(TradePartner::class, 'bill_to_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

