<?php

namespace App\Models\ApprovalMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalStage_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_appr_stage_laravel';
    protected $fillable = [
      'id',
      'stage_title',
      'stage_position',
      'stage_stat',
      'stage_module',
      'created_by',
    ];

}
