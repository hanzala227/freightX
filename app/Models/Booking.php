<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Booking extends Model {
    protected $fillable = ['booking_no', 'vessel_name', 'voyage', 'pol_name', 'pod_name', 'status'];
}
