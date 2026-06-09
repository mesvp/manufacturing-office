<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materialMaster_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_material_master_laravel';
    protected $fillable = [
      'id',
      'title',
      'uom',
      'created_by'
    ];

}
