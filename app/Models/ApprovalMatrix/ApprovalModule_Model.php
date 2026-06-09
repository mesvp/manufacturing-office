<?php

namespace App\Models\ApprovalMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalModule_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_appr_module_laravel';
    protected $fillable = [
      'id',
      'title',
      'tableName',
    ];

}
