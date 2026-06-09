<?php
namespace App\Models\InventoryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "inventory_management_approve";

    protected $fillable = [
       
        'Inventory_Management_id',
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
