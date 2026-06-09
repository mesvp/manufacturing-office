<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factory_Cell_Cutting_History extends Model
{
    use HasFactory;
	protected $table = 'tbl_factory_cellcutting_hist_laravel';
	protected $fillable = [
      'id',
      'cellId',
      'remarks',
      'action',
      'actionBy',
      'ip'
    ];
}
