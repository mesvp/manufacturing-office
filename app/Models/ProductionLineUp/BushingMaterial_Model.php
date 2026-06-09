<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BushingMaterial_Model extends Model
{
    use HasFactory;
	protected $table = 'tbl_factory_bushing_material_laravel';
	protected $fillable = [
      'id',
      'bushingId',
      'prd_matId',
      'status'
    ];
}
