<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Land_Building_Other extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_land_building_others";

    protected $fillable = [
       
        'factory_land_building_id',
        'add_field_name_manually',
        'enter_manually_details',
    ];
  
}
