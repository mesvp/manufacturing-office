<?php
namespace App\Models\InventoryManagement;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management_Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "inventory_management_product";

    protected $fillable = [
       
        'Inventory_Management_Material_id',
        'material_sl_no',  
    ];
  
}
