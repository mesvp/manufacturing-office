<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Qc_Material extends Model
{
    use HasFactory;
	protected $table = 'tbl_factory_stringer_qc_material_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'StringerQcId',
		'material',
		'stringerNo',
		'time',
		'cellPosition',
		'productionQty',
		'RejectQty',
		'reason',
		'defectCat',
		'created_at',
		'updated_at',
	];
}
