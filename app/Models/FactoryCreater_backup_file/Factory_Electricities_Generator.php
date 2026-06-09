<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Electricities_Generator extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_electricities_generators";

    protected $fillable = [
       
        'factory_id',
        'generator',
        'Generator_Capacity',
    ];
  
}
