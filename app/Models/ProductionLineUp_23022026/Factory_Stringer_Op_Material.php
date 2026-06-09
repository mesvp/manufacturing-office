<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Op_Material extends Model
{
	use HasFactory;

	protected $table = 'tbl_factory_stringer_op_material_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'stringerOpId',
		'material',
		'stringerNo',
		'time',
		'productionQty',
		'RejectQty',
		'reason',
		'defectCat',
	];
}
