<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance_Machine_Shutdown_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance_Machine_Shutdown_Details";

    protected $fillable = [
       
        'userID',
        'Organization',  
        'Manufacturing_Unit',  
        'Plant_Name',  
        'Plant_Shutdown_From',  
        'Plant_Shutdown_To',  
        'Plant_Shutdown_Reason',  
        'Total_Cost_Of_Production_Loss',  
        'remarks',  
    ];
  
}
