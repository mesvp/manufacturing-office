<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantCapacity_Model extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_plant_capacity_laravel';

    protected $fillable = [
        'id',
        'plantNo',
        'startDate',
        'hourlyNos',
        'hourlyMW',
        'dailyNos',
        'dailyMW',
        'monthlyNos',
        'monthlyMW',
        'yearlyNos',
        'yearlyMW',
        'status',
        'stage',
        'appr_process',
        'created_by'
    ];
}

