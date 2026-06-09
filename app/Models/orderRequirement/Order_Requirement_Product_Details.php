<?php
namespace App\Models\orderRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order_Requirement_Product_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "order_requirement_product_details";

    protected $fillable = [
       
        'Stock_id',
        'Material_Name',
        'HSN_Code_Second',
        'UOM_Second',
        'Total_QTY',
        'Rate',
        'Amount',
        'GST_Value',
        'Sub_Total',
    ];
  
}
