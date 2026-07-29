<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NinetyDeg_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_ninetydeg_laravel';

    protected $fillable = [
        'ninetydeg_id',
        'ninetydeg_date',
        'ninetydeg_time',
        'ninetydeg_operator',
        'ninetydeg_source',
        'ninetydeg_laminatorNo',
        'ninetydeg_batchNo',
        'ninetydeg_incharge',
        'ninetydeg_shift',
        'ninetydeg_plant',
        'ninetydeg_cycle_no',
        'ninetydeg_rfid',
        'ninetydeg_barcode',
        'status',
        'rwrk_status',
        'ninetydeg_pDefectRsn',
        'created_by',
        'scan_flag'
    ];
}
