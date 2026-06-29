<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantTargetHist_model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_plant_target_hist_laravel';

    protected $fillable = [
        'id',
        'mainTableId',
        'remarks',
        'action',
        'actionBy',
        'ip'
    ];
}
