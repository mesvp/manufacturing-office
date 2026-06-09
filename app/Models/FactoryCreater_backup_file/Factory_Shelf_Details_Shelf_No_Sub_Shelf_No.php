<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Shelf_Details_Shelf_No_Sub_Shelf_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_shelf_details_shelf_no_sub_shelf_no";

    protected $fillable = [       
        'factory_shelf_details_shelf_no_id',
        'Sub_Shelf_No',  
        'Sub_Shelf_Capacity',  
    ];  
}
