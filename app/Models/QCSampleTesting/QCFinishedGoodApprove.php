<?php
namespace App\Models\QCSampleTesting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class QCFinishedGoodApprove extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "qcfinishedgoodapprove";

    protected $fillable = [
       'QCFinishedGoodID',
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
