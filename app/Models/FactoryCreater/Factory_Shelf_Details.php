<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Shelf_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "factory_shelf_details";

    protected $fillable = [       
        'factory_id',
        'Total_Shelf',  
        'Total_Shelf_Capacity',  
        'Remark',   
    ];  
}
