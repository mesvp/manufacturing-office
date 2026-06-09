<?php
namespace App\Models\ProductCategories;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductCategories_Add_Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "productcategories_add_product";

    protected $fillable = [
       
        'userID',
        'Organization_Name',
        'Manufacturing_Unit',
        'BU',
        'Plant_Name',
        'Sub_Product',
        'Company_Name',
        'Colour',
        'Size',
        'Category',
        'Lable',
        'remarks',
    ];
  
}
