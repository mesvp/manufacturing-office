<?php
namespace App\Models\FactoryCreater;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Master_Shift extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "hr_mstr_shift";

}
