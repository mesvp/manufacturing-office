<?php
namespace App\Models\orderRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order_Requirement_Sales extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "order_requirement_sales";

    protected $fillable = [
       
        'userID',
        'Organization',
        'BU_Name',
        'Sales_Order_No',
        'Customer_Name',
        'Unit_Name',
        'Plant_Name',
        'Order_Date',
        'Company_Name',
        'Dispatch_Date',
        'Brand_Label',
        'remarks',
    ];
  
}
