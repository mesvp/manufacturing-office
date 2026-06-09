<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EL_QC_RWRK extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_el_qc_laravel_rwrk';
    protected $fillable = [
        'elqc_id',
        'elqc_date',
        'elqc_time',
        'elqc_operator',
        'elqc_source',
        'elqc_bushingNo',
        'elqc_batchNo',
        'elqc_incharge',
        'elqc_shift',
        'elqc_plant',
        'elqc_rfid',
        'elqc_barcode',
        'status',
        'created_by'
    ];
}
