<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NinetyDegDamage_Model_RWRK extends Model
{
    protected $table = 'tbl_factory_ninetydeg_defect_laravel_rwrk';

    protected $fillable = [
        'def_id',
        'ninetydeg_Id',
        'cell_no',
        'cell_qty',
        'defectRsn',
        'defectCatgry',
        'res_prsn',
        'elqc_type',
        'res_machine'
    ];
}
