<?php

namespace App\Models\ProductionLineup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FramingHistory_Model extends Model
{
    use HasFactory;

    protected $table = 'tbl_factory_framing_history_laravel';

    protected $fillable = [
        'framing_id',
        'action',
        'ip_address',
        'created_by'
    ];
}
