<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Land_Building_Boundary_Type extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_land_building_boundary_types";

    protected $fillable = [
       
        'factory_land_building_id',
        'boundary_type',
        'attachement',
    ];
  
}
