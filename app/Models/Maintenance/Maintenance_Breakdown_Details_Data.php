<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance_Breakdown_Details_Data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance_Breakdown_Details_Data";

    protected $fillable = [
       
        'Maintenance_Breakdown_Details_Id',
        'Equipment_Breakdown',  
        'Cost_Of_Spare_Part_Use',  
        'Machine_Stop_Production_From',  
        'Machine_Stop_Production_To',  
        'Cost_Of_Production_Loss',  
    ];
  
}
