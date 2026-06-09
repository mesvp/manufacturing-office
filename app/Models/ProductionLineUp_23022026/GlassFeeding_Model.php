<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassFeeding_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_glass_feed_laravel';
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
