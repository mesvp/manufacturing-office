<?php

namespace App\Models\ProductionLineup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raw_Consumption_Transac_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_consumption_transac_laravel';

    protected $fillable = [
        'id',
        'matrerial',
        'date',
        'batch',
        'qty',
        'godown',
        'organisation',
        'refNo',
        'transacCategory',
        'raisedBy',
        'ip'
    ];
}
