<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BushingDamageMaterial_Model extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_bushing_damage_material_laravel';
    protected $fillable = [
      'id',
      'bushId',
      'finishedGoodId',
      'dmgQty',
      'dmgUOM',
      'dmgReason',
      'dmgCategory',
      'status'
    ];
}
