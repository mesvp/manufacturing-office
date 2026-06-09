<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSetUpHist_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_productsetup_hist_laravel';
    protected $fillable = [
      'id',
      'batchNo',
      'remarks',
      'action',
      'actionBy',
      'ip'
    ];
}
