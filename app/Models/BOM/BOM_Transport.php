<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Transport extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_transport";

    protected $fillable = [
       
        'BOM_ID',
        'Transport',
    ];
  
}
