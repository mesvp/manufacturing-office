<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Cell_Cutting_Material extends Model
{
    use HasFactory;

    public $table = "tbl_factory_cell_cutting_material_laravel";
	protected $fillable = [
      'id',
	  'batchNo',
      'cellCuttingId',
      'material',
      'time',
      'productionQty',
      'RejectQty',
      'reason',
      'defectCat',
    ];

}
