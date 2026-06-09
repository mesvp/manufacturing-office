<?php
namespace App\Models\Master\landbuilding;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Land_Building extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "master_land_building";

    protected $fillable = [       
        'land_type',       
    ];
  
}
