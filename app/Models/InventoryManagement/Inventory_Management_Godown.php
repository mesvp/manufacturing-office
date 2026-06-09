<?php
namespace App\Models\InventoryManagement;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management_Godown extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "inventory_management_godown";

    protected $fillable = [
       
        'Inventory_Management_Product_Id',
        'Transfered_To_Godown',  
        'Godown_Name',  
        'Shelf_No',  
        'Sub_Shelf_No',  
        'Shelf_OB',  
        'Shelf_CB',  
    ];
  
}
