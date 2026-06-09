<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlassFeedingHist_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_glass_feed_hist_laravel';
    protected $fillable = [
      'id',
      'glassFeedId',
      'remarks',
      'action',
      'actionBy',
      'ip'
    ];
}
