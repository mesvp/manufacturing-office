<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Shelf_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_shelf_details";

    protected $fillable = [       
        'factory_id',
        'Total_Shelf',  
        'Total_Shelf_Capacity',  
        'Remark',   
    ];  
}
