<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance_Assign extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance_Assign";

    protected $fillable = [
       
        'userID',
        'Organization',  
        'Manufacturing_Unit',  
        'Plant_Name',
        'remarks',  
    ];
  
}
