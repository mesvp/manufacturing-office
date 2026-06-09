<?php
namespace App\Models\ProductionProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production_Process extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_process";

    protected $fillable = [
       
        'userID',
        'Product',
        'Sub_Product',
        'Sub_Sub_Product',
        'Description',
        'remarks',
    ];
  
}
