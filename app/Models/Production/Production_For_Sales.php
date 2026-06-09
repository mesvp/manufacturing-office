<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production_For_Sales extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "Production_For_Sales";

    protected $fillable = [
       
        'userID',
        'Work_Order_Type',
        'Sales_Order_No',
        'Work_Order_No',
        'Organization',
        'BU',
        'Company_Name',
        'Expected_Date',
        'Unit_Name',
        'Plant_Name',
        'Work_Order_Name',
        'Work_Order_Status',
        'remarks',
    ];
  
}
