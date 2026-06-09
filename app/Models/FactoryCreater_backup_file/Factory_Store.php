<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Store extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_stores";

    protected $fillable = [
       
        'factory_id',
        'status',
        'Total_Rack',
        'Rack_Capacity',
        'Total_Bin',
        'Total_Bin_Capacity',
        'Rack_No',
        'Rack_Capacities',
    ];
  
}
