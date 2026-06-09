<?php
namespace App\Models\orderRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Order_Requirement_Stock_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "order_requirement_stock__approve";

    protected $fillable = [
       
        'Order_Requirement_Stock_id',
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
