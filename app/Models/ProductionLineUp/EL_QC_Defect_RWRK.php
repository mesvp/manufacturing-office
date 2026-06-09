<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EL_QC_Defect_RWRK extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_el_qc_defect_laravel_rwrk';
    protected $fillable = [
        'def_id',
        'elqcId',
        'cell_no',
        'cell_qty',
        'defectRsn',
        'defectCatgry',
        'res_prsn',
        'elqc_type',
        'res_machine',
        'status'
    ];
}
