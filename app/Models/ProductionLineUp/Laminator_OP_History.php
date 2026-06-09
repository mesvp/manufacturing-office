<?php

namespace App\Models\ProductionLineUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laminator_OP_History extends Model
{
    use HasFactory;
    protected $table = 'tbl_factory_laminator_history_laravel';

    protected $fillable = [
        'laminator_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
