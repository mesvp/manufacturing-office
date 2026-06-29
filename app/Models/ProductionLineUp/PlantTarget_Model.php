<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantTarget_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_plant_target_laravel';

    protected $fillable = [
        'id',
        'plantNo',
        'startDate',
        'endDate',
        'targetNos',
        'targetMW',
        'status',
        'stage',
        'appr_process',
        'created_by'
    ];
}
