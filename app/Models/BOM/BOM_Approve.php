<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "bom_approve";

    protected $fillable = [
       
        'BOM_id',
        'userID',
        'action',
        'action_by',
        'role',
        'comment_cat',
        'comment_text',
    ];
  
}
