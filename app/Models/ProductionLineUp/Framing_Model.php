<?php

namespace App\Models\ProductionLineup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Framing_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_framing_laravel';

    protected $fillable = [
        'framing_id',
        'framing_date',
        'framing_time',
        'framing_operator',
        'framing_source',
        'framing_QC',
        'framing_batchNo',
        'framing_incharge',
        'framing_shift',
        'framing_plant',
        'framing_cycle_no',
        'framing_rfid',
        'framing_barcode',
        'status',
        'framing_pDefectRsn',
        'created_by'
    ];
}
