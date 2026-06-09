<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Stores_Sub_Rack_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_stores_sub_rack_nos";

    protected $fillable = [
       
        'factory_stores_id',
        'Sub_Rack_No',
        'Sub_Rack_Capacity',
    ];
  
}
