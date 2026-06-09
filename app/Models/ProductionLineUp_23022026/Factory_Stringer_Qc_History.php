<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Qc_History extends Model
{
    use HasFactory;
	protected $table = 'tbl_factory_stringer_qc_history_laravel';
	protected $fillable = [
		'id',
		'strqcId',
		'remarks',
		'action',
		'actionBy',
		'ip'
	];
}
