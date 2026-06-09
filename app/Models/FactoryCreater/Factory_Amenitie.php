<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Amenitie extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_amenities";

    protected $fillable = [
       
        'factory_id',
        'Toilet_Count',
        'For_Men',
        'For_Women',
        'WashBasin_Count',
        'Urinals',
    ];
  
}
