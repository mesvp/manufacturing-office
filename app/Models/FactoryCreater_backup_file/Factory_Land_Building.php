<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Land_Building extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_land_building";

    protected $fillable = [
       
        'land_type',
        'land_area',
        'open_area',
        'cover_area',
        'building_area',
        'building_type',
        'boundary_height',
        'boundary_width',
        'window',
        'gate',
        'remark',       
    ];
  
}
