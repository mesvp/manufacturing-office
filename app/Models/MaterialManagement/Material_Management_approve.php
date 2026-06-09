<?php
namespace App\Models\MaterialManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material_Management_approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "material_management_approve";

    protected $fillable = [
       
        'factory_id',
        'userID',
        'status',
        'Stage',
        'action',
        'pre_post_approval',
        'role',
        'comment_text',
        'hold',
        'reason_for_hold',
        'days_for_holding',
        'Forward_To',
        'ip_address',
        'device_name',
    ];  
}
