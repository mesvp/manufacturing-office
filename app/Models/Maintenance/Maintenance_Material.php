<?php
namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Maintenance_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Maintenance_Material";

    protected $fillable = [
       
        'Maintenance_id',
        'Material_Type',  
        'Material_Name',  
        'Quantity',  
    ];
  
}
