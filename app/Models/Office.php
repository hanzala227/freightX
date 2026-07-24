<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tradePartners()
    {
        return $this->hasMany(TradePartner::class, 'sales_office_id');
    }

    public function oceanImports()
    {
        return $this->hasMany(OceanImport::class);
    }

    public function oceanExports()
    {
        return $this->hasMany(OceanExport::class);
    }
}
