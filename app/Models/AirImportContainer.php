<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirImportContainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'air_import_id',
        'container_no', 'pp_ctf', 'container_type', 'seal_no', 'seal_no2',
        'lfd', 'fdd',
        'pkg_qty', 'weight_kg', 'weight_lb', 'measure_cbm', 'measure_cft',
        'pickup_no', 'cprs_no', 'cnru_no', 'it_no', 'is_dg',
        'storage_start_date', 'storage_end_date',
        'is_carrier_release', 'yard_location', 'unload_vessel_date',
        'gate_in_date', 'rail_start_date', 'pod_eta', 'is_avail_pickup',
        'appointment_date', 'trucker_id', 'pickup_date', 'gate_out_date',
        'fdest_eta', 'eta_door', 'ata_door', 'empty_conf_date', 'empty_ret_date',
        'is_complete', 'remarks', 'internal_remarks',
    ];

    protected $casts = [
        'is_dg' => 'boolean',
        'is_carrier_release' => 'boolean',
        'is_avail_pickup' => 'boolean',
        'is_complete' => 'boolean',
        'lfd' => 'date',
        'fdd' => 'date',
        'storage_start_date' => 'date',
        'storage_end_date' => 'date',
        'unload_vessel_date' => 'date',
        'gate_in_date' => 'date',
        'rail_start_date' => 'date',
        'pod_eta' => 'date',
        'appointment_date' => 'date',
        'pickup_date' => 'date',
        'gate_out_date' => 'date',
        'fdest_eta' => 'date',
        'eta_door' => 'date',
        'ata_door' => 'date',
        'empty_conf_date' => 'date',
        'empty_ret_date' => 'date',
    ];

    public function airImport()
    {
        return $this->belongsTo(AirImport::class);
    }

    public function trucker()
    {
        return $this->belongsTo(TradePartner::class, 'trucker_id');
    }
}
