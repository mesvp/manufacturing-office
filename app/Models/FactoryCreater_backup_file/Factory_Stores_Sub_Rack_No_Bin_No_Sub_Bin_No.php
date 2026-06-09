<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_stores_sub_rack_no_bin_no_sub_bin_nos";

    protected $fillable = [
       
        'factory_stores_sub_rack_no_bin_no_id',
        'Sub_Bin_No',
        'Sub_Bin_Capacity',
    ];
  
}
