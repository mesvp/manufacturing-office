<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_ReWork_History extends Model
{
	use HasFactory;
	protected $table = 'tbl_factory_stringer_rw_history_laravel';
	protected $fillable = [
		'id',
		'strReworkId',
		'remarks',
		'action',
		'actionBy',
		'ip',
		'created_by',
		'created_at',
		'updated_at',
	];
}
