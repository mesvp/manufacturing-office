<?php

namespace App\Models\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "rawmaterial";

    protected $fillable = [

        'RawMaterial_stock_id',
        'Organization',
        'Manufacturing_Unit',
        'Godown_name',
        'date',
    ];  
}
