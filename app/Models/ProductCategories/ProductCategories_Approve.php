<?php
namespace App\Models\ProductCategories;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductCategories_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "productcategories_approve";

    protected $fillable = [       
        'Product_id',
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