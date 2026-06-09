<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Plant_Machineries_Warranty extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_plant_machineries_warranty";

    protected $fillable = [
       
        'factory_plant_machineries_id',
        'Warranty',
        'Production_Capacitys',
        'UOMs',
    ];
  
}
