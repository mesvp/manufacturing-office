<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalQC_Defect_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_fqc_defect_laravel';
    protected $fillable = [
        'def_id',
        'fqc_id',
        'cell_no',
        'cell_qty',
        'defectRsn',
        'defectCatgry',
        'res_prsn',
        'res_machine'
    ];
}

