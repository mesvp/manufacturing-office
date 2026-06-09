<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance_Breakdown_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance_Breakdown_Details";

    protected $fillable = [
       
        'userID',
        'Organization',  
        'Manufacturing_Unit',  
        'Plant_Name',  
        'Machine_Name',  
        'Purchase_Date',  
        'Expird_Date',  
        'Life_Span_Of_Equipment	',  
        'remarks',  
    ];
  
}
