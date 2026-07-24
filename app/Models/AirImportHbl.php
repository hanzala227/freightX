<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirImportHbl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'air_import_id', 'hawb_no', 'customer_id', 'shipper_id', 'consignee_id',
        'notify_party_id', 'sales_person_id', 'bill_to_id', 'customs_broker_id',
        'quotation_id', 'hsn_code', 'is_frt_released', 'frt_released_date',
        'pkg_qty', 'pkg_unit_id',
        'gross_weight', 'chargeable_weight', 'volume', 'commodity',
        'incoterms_id', 'freight_term', 'is_an_sent', 'an_sent_date',
        'is_do_sent', 'do_sent_date', 'hbl_remark',
        'mark_text', 'description_text',
        'sub_hawbs', 'commodities', 'po_numbers', 'warehouse_receipts',
        'hbl_is_ecommerce', 'hawb_memos',
        'is_blocked', 'op_id',
    ];

    protected $casts = [
        'is_an_sent' => 'boolean',
        'is_do_sent' => 'boolean',
        'is_frt_released' => 'boolean',
        'frt_released_date' => 'date',
        'an_sent_date' => 'date',
        'do_sent_date' => 'date',
        'sub_hawbs' => 'array',
        'commodities' => 'array',
        'po_numbers' => 'array',
        'warehouse_receipts' => 'array',
        'hbl_is_ecommerce' => 'boolean',
        'hawb_memos' => 'array',
    ];

    public function airImport() { return $this->belongsTo(AirImport::class); }
    public function customer() { return $this->belongsTo(TradePartner::class, 'customer_id'); }
    public function shipper() { return $this->belongsTo(TradePartner::class, 'shipper_id'); }
    public function consignee() { return $this->belongsTo(TradePartner::class, 'consignee_id'); }
    public function notifyParty() { return $this->belongsTo(TradePartner::class, 'notify_party_id'); }
    public function salesPerson() { return $this->belongsTo(User::class, 'sales_person_id'); }
    public function packageUnit() { return $this->belongsTo(PackageUnit::class, 'pkg_unit_id'); }
    public function op() { return $this->belongsTo(User::class, 'op_id'); }
    
    public function charges() { return $this->morphMany(Charge::class, 'chargeable'); }
}
