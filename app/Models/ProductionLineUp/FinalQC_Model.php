<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalQC_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_fqc_laravel';

    protected $fillable = [
        'fqc_id',
        'fqc_date',
        'fqc_time',
        'fqc_operator',
        'fqc_source',
        'fqc_QC',
        'fqc_batchNo',
        'fqc_incharge',
        'fqc_shift',
        'fqc_plant',
        'fqc_cycle_no',
        'fqc_rfid',
        'fqc_barcode',
        'status',
        'fqc_pDefectRsn',
        'created_by',
        'scan_grade',
        'scan_flag'
    ];
}
