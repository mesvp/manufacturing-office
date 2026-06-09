<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom";

    protected $fillable = [
       
        'userID',
        'Organization',
        'Manufacturing_Unit',
        'Plant_Name',
        'Category',
        'Product',
        'Sub_Product',
        'Sub_Sub_Product',
        'Color',
        'Total_Amout',
        'remarks',
    ];
  
}
