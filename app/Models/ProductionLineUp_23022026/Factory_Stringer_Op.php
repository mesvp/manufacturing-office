<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Op extends Model
{
	use HasFactory;

	protected $table = 'tbl_factory_stringer_op_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'date',
		'shift',
		'plant',
		'strNo',
        'operator',
		'checker',
		'created_by',
		'status',
		'stage',
		'created_at',
		'updated_at',
	];
}
