<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance";

    protected $fillable = [
       
        'userID',
        'Organization',  
        'Manufacturing_Unit',  
        'Plant_Name',  
        'Machine_Name',  
        'Assign_To',  
        'Frequency',  
        'Start_Date',  
        'Breakdown_Details',  
        'Material_Required',  
        'Material_Cost',  
        'remarks',   
    ];
  
}
