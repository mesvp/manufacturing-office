<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Op_History extends Model
{
	use HasFactory;

	protected $table = 'tbl_factory_stringer_op_history_laravel';
	protected $fillable = [
		'id',
		'stropId',
		'remarks',
		'action',
		'actionBy',
		'ip'
	];
}
