<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Name_Of_Unit extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_name_of_units";

    protected $fillable = [
       
        'name_of_unit',
    ];
  
}
