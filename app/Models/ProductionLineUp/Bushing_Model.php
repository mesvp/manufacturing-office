<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bushing_Model extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_bushing_laravel';
    protected $fillable = [
      'bushing_id',
      'bushing_date',
      'bushing_time',
      'bushing_operator',
      'bushing_batchNo',
      'bushing_incherge',
      'bushing_shift',
      'bushing_plant',
      'bushing_logo',
      'bushing_hasDamage',
      'bushing_rfid',
      'bushing_barCode',
      'created_by',
      'scan_flag'
    ];
}
