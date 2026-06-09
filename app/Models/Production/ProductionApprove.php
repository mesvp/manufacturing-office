<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductionApprove extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_approve";

    protected $fillable = [
       'productionID',
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
