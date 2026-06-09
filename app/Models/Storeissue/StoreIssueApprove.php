<?php
namespace App\Models\Storeissue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StoreIssueApprove extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "store_issue_approve";

    protected $fillable = [
       
        'Store_Requistion_id',
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