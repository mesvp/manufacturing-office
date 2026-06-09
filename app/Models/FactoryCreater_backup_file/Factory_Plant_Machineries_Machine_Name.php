<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Plant_Machineries_Machine_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_plant_machineries_machine_names";

    protected $fillable = [
       
        'factory_plant_machineries_id',
        'Machine_Name',
        'Attachement',
        'Machine_Code',
        'Accessories',
        'Attachements',
        'Specification',
        'Make_Model',    
    ];
  
}
