<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JB_Damage_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_jb_defect_laravel';

    protected $fillable = [
        'jb_Id',
        'type',
        'size',
        'brand',
        'qty',
        'uom'
    ];
}
