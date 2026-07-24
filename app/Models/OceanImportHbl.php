<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OceanImportHbl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ocean_import_id', 'hbl_no', 'quotation_no', 'customer_id', 
        'sales_person_id', 'shipper_id', 'consignee_id', 'notify_party_id', 
        'customs_broker_id', 'delivery_location_id', 'referred_by_id',
        'pod_id', 'del_id', 'fdest_id', 'receipt_id', 'vessel_name', 
        'voyage_no', 'pre_carriage_by', 'service_term', 'ship_mode', 
        'ship_type', 'cargo_type', 'incoterms_id', 'date_of_issue', 
        'lc_no', 'sc_no', 'freight_payable_at', 'is_express_bl', 
        'is_door_move', 'is_customs_clear', 'is_customs_hold', 
        'is_obl_received', 'obl_received_date', 'is_fr_released', 
        'fr_released_date', 'is_an_sent', 'an_sent_date', 'is_do_sent', 
        'do_sent_date', 'name_account', 'group_comm', 'line_code', 
        'is_ecommerce', 'is_customs_doc', 'hbl_remark',
        'cfs_location_id', 'is_rail', 'freight_released_by_id',
        'po_no', 'po_mapping_type', 'hbl_mark', 'hbl_description',
        'arrival_notice_remark', 'delivery_order_remark'
    ];

    protected $attributes = [
        'hbl_no' => '',
        'quotation_no' => '',
        'vessel_name' => '',
        'voyage_no' => '',
        'pre_carriage_by' => '',
        'service_term' => '',
        'ship_mode' => '',
        'ship_type' => '',
        'cargo_type' => '',
        'freight_payable_at' => '',
        'is_express_bl' => 0,
        'is_door_move' => 0,
        'is_customs_clear' => 0,
        'is_customs_hold' => 0,
        'is_obl_received' => 0,
        'is_fr_released' => 0,
        'is_an_sent' => 0,
        'is_do_sent' => 0,
        'is_ecommerce' => 0,
        'is_customs_doc' => 0,
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'obl_received_date' => 'date',
        'fr_released_date' => 'date',
        'an_sent_date' => 'date',
        'do_sent_date' => 'date',
        'is_express_bl' => 'boolean',
        'is_door_move' => 'boolean',
        'is_customs_clear' => 'boolean',
        'is_customs_hold' => 'boolean',
        'is_obl_received' => 'boolean',
        'is_fr_released' => 'boolean',
        'is_an_sent' => 'boolean',
        'is_do_sent' => 'boolean',
        'is_ecommerce' => 'boolean',
        'is_customs_doc' => 'boolean',
        'is_rail' => 'boolean',
    ];

    public function oceanImport() { return $this->belongsTo(OceanImport::class); }
    public function customer() { return $this->belongsTo(TradePartner::class, 'customer_id'); }
    public function shipper() { return $this->belongsTo(TradePartner::class, 'shipper_id'); }
    public function consignee() { return $this->belongsTo(TradePartner::class, 'consignee_id'); }
    public function cfsLocation() { return $this->belongsTo(TradePartner::class, 'cfs_location_id'); }
    public function deliveryLocation() { return $this->belongsTo(TradePartner::class, 'delivery_location_id'); }
    public function freightReleasedBy() { return $this->belongsTo(User::class, 'freight_released_by_id'); }
    
    public function containers()
    {
        return $this->belongsToMany(OceanImportContainer::class, 'ocean_import_container_hbl', 'hbl_id', 'container_id')
                    ->withPivot(['pkg_qty', 'pkg_unit', 'weight_kg', 'weight_unit', 'measure_cbm', 'measure_unit', 'po_no'])
                    ->withTimestamps();
    }

    public function commodities()
    {
        return $this->hasMany(OceanImportHblCommodity::class, 'hbl_id');
    }

    public function receipts()
    {
        return $this->hasMany(OceanImportHblReceipt::class, 'hbl_id');
    }

    public function charges() { return $this->morphMany(Charge::class, 'chargeable'); }
    public function oceanImportCharges() { return $this->hasMany(\App\Models\OceanImportCharge::class, 'ocean_import_hbl_id'); }
}
