<?php
namespace App\Models\orderRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order_Requirement_Sales_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "order_requirement_sales__approve";

    protected $fillable = [
       
        'Order_Requirement_Sales_id',
        'userID',
        'status',
        'Stage',
        'action',
        'pre_post_approval',
        'role',
        'comment_text',
        'days_for_holding',
        'Forward_To',
        'ip_address',
        'device_name',
    ];
  
}
