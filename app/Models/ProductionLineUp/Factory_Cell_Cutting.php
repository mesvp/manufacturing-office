<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Cell_Cutting extends Model
{
	use HasFactory;

	protected $table = 'tbl_factory_cell_cutting_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'date',
		'shift',
		'operator',
		'checker',
		'created_by',
		'status',
		'stage',
		'created_at',
		'updated_at',
	];
}
