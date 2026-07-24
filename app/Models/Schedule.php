<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $fillable = [
        'schedule_no', 'vessel_name', 'voyage', 'pol_name', 'pod_name', 'etd', 'eta',
        'carrier_bkg_no', 'shipping_agent', 'office_id', 'itn_no',
        'oversea_agent_id', 'bl_type', 'notify_id', 'op_id', 'post_date',
        'forwarding_agent_id', 'vessel_id', 'pol_id', 'pod_id', 'fdest_id',
        'final_eta', 'delivery_to_pier', 'por_id', 'del_id', 'empty_pickup',
        'por_etd', 'del_eta', 'freight', 'obl_type', 'on_board_date', 'ship_mode',
        'doc_cutoff', 'svc_term_from_id', 'svc_term_to_id', 'port_cutoff', 'rail_cutoff',
        'carrier_id', 'actual_shipper_id', 'customer_id', 'bill_to_id', 'consignee_id',
        'trucker_id', 'referred_by_id', 'cargo_type', 'cargo_pickup', 'cargo_ready',
        'wh_cutoff', 'vgm_cutoff', 'color', 'internal_message', 'containers_data', 'memos_data',
    ];

    protected $casts = [
        'etd'         => 'date',
        'eta'         => 'date',
        'final_eta'   => 'date',
        'post_date'   => 'date',
        'por_etd'     => 'date',
        'del_eta'     => 'date',
        'on_board_date' => 'date',
        'doc_cutoff'  => 'date',
        'port_cutoff' => 'date',
        'rail_cutoff' => 'date',
        'cargo_ready' => 'date',
        'wh_cutoff'   => 'date',
        'vgm_cutoff'  => 'date',
        'containers_data' => 'array',
        'memos_data' => 'array',
    ];

    public function charges() { return $this->morphMany(Charge::class, 'chargeable'); }
    public function documents() { return $this->morphMany(Document::class, 'documentable'); }
    public function statusLogs() { return $this->morphMany(ShipmentStatusLog::class, 'shipment'); }
    public function workOrders() { return $this->morphMany(WorkOrder::class, 'workable'); }

    public function office() { return $this->belongsTo(Office::class); }
    public function vessel() { return $this->belongsTo(Vessel::class); }
    public function pol() { return $this->belongsTo(Port::class, 'pol_id'); }
    public function pod() { return $this->belongsTo(Port::class, 'pod_id'); }
    public function fdest() { return $this->belongsTo(Port::class, 'fdest_id'); }
    public function por() { return $this->belongsTo(Port::class, 'por_id'); }
    public function del() { return $this->belongsTo(Port::class, 'del_id'); }
    public function op() { return $this->belongsTo(User::class, 'op_id'); }
    public function svcTermFrom() { return $this->belongsTo(ServiceTerm::class, 'svc_term_from_id'); }
    public function svcTermTo() { return $this->belongsTo(ServiceTerm::class, 'svc_term_to_id'); }
    public function carrier() { return $this->belongsTo(TradePartner::class, 'carrier_id'); }
    public function overseaAgent() { return $this->belongsTo(TradePartner::class, 'oversea_agent_id'); }
    public function notify() { return $this->belongsTo(TradePartner::class, 'notify_id'); }
    public function forwardingAgent() { return $this->belongsTo(TradePartner::class, 'forwarding_agent_id'); }
    public function actualShipper() { return $this->belongsTo(TradePartner::class, 'actual_shipper_id'); }
    public function customer() { return $this->belongsTo(TradePartner::class, 'customer_id'); }
    public function billTo() { return $this->belongsTo(TradePartner::class, 'bill_to_id'); }
    public function consignee() { return $this->belongsTo(TradePartner::class, 'consignee_id'); }
    public function trucker() { return $this->belongsTo(TradePartner::class, 'trucker_id'); }
    public function referredBy() { return $this->belongsTo(TradePartner::class, 'referred_by_id'); }
}
