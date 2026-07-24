<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vessel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'imo_no',
        'mmsi_no',
        'flag',
    ];

    public function oceanImports()
    {
        return $this->hasMany(OceanImport::class);
    }

    public function oceanExports()
    {
        return $this->hasMany(OceanExport::class);
    }
}
