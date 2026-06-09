<?php

namespace App\Models\ProductionLineup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FramingDamage_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_framing_defect_laravel';

    protected $fillable = [
        'framing_Id',
        'type',
        'size',
        'brand',
        'qty'
    ];
}
