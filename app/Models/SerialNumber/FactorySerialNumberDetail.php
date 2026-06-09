<?php
namespace App\Models\SerialNumber;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FactorySerialNumberDetail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_serial_number_details";

    public function factorySerialNumber()
    {
        return $this->hasMany(FactorySerialNumberDetail::class, 'sl_id');
    }


  
}
