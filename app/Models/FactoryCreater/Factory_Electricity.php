<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Electricity extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_electricities";

    protected $fillable = [
       
        'factory_id',
        'Total_Capacity',
        'Running_Capacity',
        'Meter',
        'Sub_Meter',
        'Source_Of_Electricity',
    ];
  
}
