<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JB_Hist_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_jb_history_laravel';

    protected $fillable = [
        'jb_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
