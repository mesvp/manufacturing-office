<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Manpower extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_manpower";

    protected $fillable = [
       
        'BOM_ID',
        'Manpower_Skill',
        'Manpower_Count',
        'Average_Salary',
    ];
  
}
