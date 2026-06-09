<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSetUpMaterial_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_production_setup_material_laravel';
    protected $fillable = [
      'id',
      'batchNo',
      'material',
      'size',
      'brand',
      'qty',
      'uom',
      'bomMat',  
      'bomQty',  
      'useStage'
    ];
}
