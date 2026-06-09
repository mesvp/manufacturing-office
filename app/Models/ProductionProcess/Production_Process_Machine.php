<?php
namespace App\Models\ProductionProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production_Process_Machine extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_process_machine";

    protected $fillable = [
       
        'Production_Process_Stage_Data_Id',
        'Machine_Name',
        'Machine_Code',
        'Machine_Company',
        'Make_Model',
        'Date_of_Purchase',
        'Preventive_Maintenance',
    ];
  
}
