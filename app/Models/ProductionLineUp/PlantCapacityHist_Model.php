<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantCapacityHist_Model extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_plant_capacity_hist_laravel';

    protected $fillable = [
        'id',
        'mainTableId',
        'remarks',
        'action',
        'actionBy',
        'ip'
    ];
}

