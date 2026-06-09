<?php
namespace App\Models\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RawMaterial_approve extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "rawmaterial_approve";

    protected $fillable = [
       
        'RawMaterial_stock__id',
        'userID',
        'action',
        'action_by',
        'role',
        'comment_cat',
        'comment_text',
    ];
  
}
