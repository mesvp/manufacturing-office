<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NinetyDegHist_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_ninetydeg_history_laravel';

    protected $fillable = [
        'ninetydeg_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
