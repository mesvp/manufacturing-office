<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassFeedingMaterial_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_glass_feed_material_laravel';
    protected $fillable = [
      'id',
      'glassFeedId',
      'material',
      'size',
      'time',
      'productionQty',
      'RejectQty',
      'reason',
      'defectCat'
    ];
}
