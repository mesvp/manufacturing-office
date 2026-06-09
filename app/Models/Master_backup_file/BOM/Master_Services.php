<?php
namespace App\Models\Master\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Services extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Master_Services";

    protected $fillable = [       
        'Services',
    ];
  
}