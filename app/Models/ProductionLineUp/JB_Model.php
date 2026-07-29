<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JB_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_jb_laravel';

    protected $fillable = [
        'jb_id',
        'jb_date',
        'jb_time',
        'jb_operator',
        'jb_source',
        'jb_QC',
        'jb_batchNo',
        'jb_incharge',
        'jb_shift',
        'jb_plant',
        'jb_cycle_no',
        'jb_rfid',
        'jb_barcode',
        'status',
        'jb_pDefectRsn',
        'created_by',
        'scan_flag'
    ];
}
