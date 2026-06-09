<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrimmingHist_Model extends Model
{
    use HasFactory;

     protected $table = 'tbl_factory_trimming_history_laravel';

    protected $fillable = [
        'trimming_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
