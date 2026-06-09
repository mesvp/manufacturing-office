<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Stores_Sub_Rack_No_Bin_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_stores_sub_rack_no_bin_nos";

    protected $fillable = [
       
        'factory_stores_sub_rack_no_id',
        'Bin_No',
        'Bin_Capacity',
    ];
  
}
