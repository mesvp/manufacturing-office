<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetUp_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_production_setup_laravel';
    protected $fillable = [
      'id',
      'batchNo',
      'plantNo',
      'startDate',
      'fromShift',
      'wattage',
      'finishGood',
      'cellRow',
      'celColumn',
      'comment',
      'status',
      'stage',
      'created_by'
    ];

}
