<?php
namespace App\Models\ThirdParty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThirdParty_RawMaterial_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "ThirdParty_RawMaterial_Approve";

    protected $fillable = [
       
        'ThirdParty_RawMaterial_id',
        'userID',
        'action',
        'action_by',
        'role',
        'comment_cat',
        'comment_text',
        'hold',
        'reason_for_hold',
        'days_for_holding',
    ];
  
}
