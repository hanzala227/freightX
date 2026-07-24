<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OceanImportContainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ocean_import_id', 'container_no', 'pp_ctf', 'container_type_id', 
        'seal_no', 'seal_no2', 'lfd', 'fdd', 'storage_start_date', 
        'storage_end_date', 'unload_vessel_date', 'gate_in_date', 
        'rail_start_date', 'pod_eta', 'appointment_date', 'pickup_date', 
        'gate_out_date', 'fdest_eta', 'eta_door', 'ata_door', 'empty_conf_date', 
        'empty_ret_date', 'pkg_qty', 'pkg_unit_id', 'weight_kg', 'weight_lb', 
        'measure_cbm', 'measure_cft', 'pickup_no', 'cprs_no', 'cnru_no', 
        'it_no', 'chassis_days', 'is_customs_hold', 'is_an_sent', 
        'an_sent_date', 'is_do_sent', 'do_sent_date', 'is_dg', 
        'is_carrier_release', 'yard_location', 
        'is_avail_pickup', 'trucker_id', 'is_complete', 'remarks', 
        'internal_remarks'
    ];

    protected $casts = [
        'lfd' => 'date', 'fdd' => 'date', 'storage_start_date' => 'date',
        'storage_end_date' => 'date', 'unload_vessel_date' => 'date',
        'gate_in_date' => 'date', 'rail_start_date' => 'date',
        'pod_eta' => 'date', 'appointment_date' => 'date',
        'pickup_date' => 'date', 'gate_out_date' => 'date',
        'fdest_eta' => 'date', 'eta_door' => 'date', 'ata_door' => 'date',
        'empty_conf_date' => 'date', 'empty_ret_date' => 'date',
        'chassis_days' => 'decimal:1',
        'an_sent_date' => 'date', 'do_sent_date' => 'date',
        'is_dg' => 'boolean', 'is_carrier_release' => 'boolean',
        'is_customs_hold' => 'boolean', 'is_an_sent' => 'boolean',
        'is_do_sent' => 'boolean', 'is_avail_pickup' => 'boolean', 'is_complete' => 'boolean',
    ];

    public function oceanImport() { return $this->belongsTo(OceanImport::class); }
    public function containerType() { return $this->belongsTo(ContainerType::class); }
    public function packageUnit() { return $this->belongsTo(PackageUnit::class, 'pkg_unit_id'); }
    public function trucker() { return $this->belongsTo(TradePartner::class, 'trucker_id'); }

    public function hbls()
    {
        return $this->belongsToMany(OceanImportHbl::class, 'ocean_import_container_hbl', 'container_id', 'hbl_id');
    }
}
