<?php
namespace App\Models\StoreTransfer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mrn_Stock_Transfer_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "mrn_stock_transfer_approve";

    protected $fillable = [
        'Store_Transfer_id',
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
