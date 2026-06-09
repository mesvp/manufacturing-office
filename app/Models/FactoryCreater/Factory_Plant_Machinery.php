<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Plant_Machinery extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_plant_machineries";

    protected $fillable = [
       
        'factory_id',
        'Plant_Name',
        'Production_Capacity',
        'Product',
        'Sub_product',
        'Sub_Sub_product',
        'UOM',
        'Duration',
        'Date_Of_Purchase',
        'Machine_Company_Name',
        'Remarks',       
    ];
  
}
