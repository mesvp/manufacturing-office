<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Office_Asset extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_office_assets";

    protected $fillable = [
       
        'factory_id',
        'Asset_Category',
        'Remark',
    ];
  
}
