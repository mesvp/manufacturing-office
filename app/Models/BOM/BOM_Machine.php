<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Machine extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_machine";

    protected $fillable = [
       
        'BOM_ID',
        'Machine_Specification',
        'Production_Capacity_Per_Shift',
        'UOM_Second',
    ];
  
}
