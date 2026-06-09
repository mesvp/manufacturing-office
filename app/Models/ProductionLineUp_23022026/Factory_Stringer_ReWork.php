<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_ReWork extends Model
{
	use HasFactory;
	protected $table = 'tbl_factory_stringer_rw_laravel';
	protected $fillable = [
		'id',
		'batchNo',
		'date',
		'shift',
		'plant',
		'operator',
		'checker',
		'status',
		'stage',
		'created_by'
	];
}
