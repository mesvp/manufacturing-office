<?php
namespace App\Models\Master\landbuilding;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Boundary_Width extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Master_Boundary_Width";

    protected $fillable = [       
        'Boundary_Width',       
    ];
  
}