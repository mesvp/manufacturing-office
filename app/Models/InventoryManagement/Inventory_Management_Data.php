<?php
namespace App\Models\InventoryManagement;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management_Data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "inventory_management_data";

    protected $fillable = [
       
        'Inventory_Management_Id',
        'Organization',  
        'Manufacturing_Unit',  
        'Plant_Name',  
        'Category', 
    ];
  
}
