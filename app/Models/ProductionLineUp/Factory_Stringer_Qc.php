<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Stringer_Qc extends Model
{
    use HasFactory;
	protected $table = 'tbl_factory_stringer_qc_laravel';
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
		'created_by',
		'created_at',
		'updated_at',
	];
}
