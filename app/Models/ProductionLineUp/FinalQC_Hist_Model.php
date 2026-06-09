<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalQC_Hist_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_fqc_history_laravel';

    protected $fillable = [
        'fqc_id',
        'action',
        'ip_address',
        'created_by'
    ];

}
