<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EL_QC_History extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_el_qc_history_laravel';
    protected $fillable = [
        'el_qc_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
