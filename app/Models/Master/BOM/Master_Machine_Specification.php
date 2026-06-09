<?php
namespace App\Models\Master\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Machine_Specification extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "master_machine_specification";

    protected $fillable = [       
        'Machine_Specification',
    ];
  
}