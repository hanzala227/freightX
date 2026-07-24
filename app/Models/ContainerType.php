<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'size_feet',
        'max_weight_kg',
        'max_cbm',
    ];

    public function oceanImportContainers()
    {
        return $this->hasMany(OceanImportContainer::class);
    }
}
