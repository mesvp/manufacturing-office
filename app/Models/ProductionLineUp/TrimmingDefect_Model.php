<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimmingDefect_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_trimming_defect_laravel';

    protected $fillable = [
        'def_id',
        'trimming_Id',
        'cell_no',
        'cell_qty',
        'defectRsn',
        'defectCatgry',
        'res_prsn',
        'elqc_type',
        'res_machine'
    ];
}
