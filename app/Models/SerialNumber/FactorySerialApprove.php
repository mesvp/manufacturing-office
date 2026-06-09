<?php
namespace App\Models\SerialNumber;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FactorySerialApprove extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_serial_approve";
  
}
