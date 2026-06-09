<?php
namespace App\Models\orderRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order_Requirement_Stock extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "order_requirement_stock";

    protected $fillable = [
       
        'userID',
        'Organization',
        'BU_Name',
        'Stock_Order_No',
        'Factory_Godown_Name',
        'Unit_Name',
        'Plant_Name',
        'Expected_Date',
        'Company_Name',
        'remarks',
    ];
  
}
