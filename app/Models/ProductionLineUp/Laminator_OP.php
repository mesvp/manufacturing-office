<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laminator_OP extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_laminator_laravel';

    protected $fillable = [
        'laminator_id',
        'laminator_date',
        'laminator_time',
        'laminator_operator',
        'laminator_source',
        'laminator_elqcNo',
        'laminator_batchNo',
        'laminator_incharge',
        'laminator_shift',
        'laminator_plant',
        'laminator_cycle_no',
        'laminator_rfid',
        'laminator_barcode',
        'status',
        'rwrk_status',
        'created_by'
    ];
}
