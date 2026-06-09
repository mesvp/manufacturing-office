<?php

namespace App\Models\ProductionLineup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallete_Model extends Model
{
    use HasFactory;

    protected $table = 'tble_pallete';

    protected $fillable = [
        'pallete',
        'serial',
        'uploader'
    ];
}
