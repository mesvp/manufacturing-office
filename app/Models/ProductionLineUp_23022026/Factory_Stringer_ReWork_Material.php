<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_ReWork_Material extends Model
{
	use HasFactory;
	protected $table = 'tbl_factory_stringer_rw_material_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'StringerRwId',
		'material',
		'tableNo',
		'time',
		'cellPosition',
		'productionQty',
		'RejectQty',
		'reason',
		'defectCat',
		'created_by',
	];
}
