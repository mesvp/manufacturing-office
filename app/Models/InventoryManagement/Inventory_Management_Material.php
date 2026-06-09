<?php
namespace App\Models\InventoryManagement;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "inventory_management_material";

    protected $fillable = [
       
        'Inventory_Management_Product_Id',
        'Material',  
        'Rack_No',  
        'Sub_Rack_No',  
        'Bin_No',  
        'Sub_Bin_No',  
    ];
  
}
