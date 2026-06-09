<?php

namespace App\Models\ApprovalMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approver_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_appr_laravel';
    protected $fillable = [
      'id',
      'stage_id',
      'person_id',
      'status',
      'created_by',
    ];
}
