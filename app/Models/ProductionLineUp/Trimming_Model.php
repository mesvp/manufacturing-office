<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trimming_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_trimming_laravel';

    protected $fillable = [
        'trimming_id',
        'trimming_date',
        'trimming_time',
        'trimming_operator',
        'trimming_source',
        'trimming_laminatorNo',
        'trimming_batchNo',
        'trimming_incharge',
        'trimming_shift',
        'trimming_plant',
        'trimming_cycle_no',
        'trimming_rfid',
        'trimming_barcode',
        'status',
        'created_by'
    ];
}
