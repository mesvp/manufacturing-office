<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Plant_Machineries_Other extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_plant_machineries_others";

    protected $fillable = [
       
        'factory_plant_machineries_id',
        'Add_Field_Name_Manually',
        'Enter_Manually_Details',
        'UOM',
    ];
  
}
