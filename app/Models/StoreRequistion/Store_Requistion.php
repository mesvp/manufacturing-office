<?php
namespace App\Models\StoreRequistion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Store_Requistion extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "store_requistion";

    protected $fillable = [
       
        'userID',
        'Request_No',
        'Date',
        'Work_Order_No',
    ];
  
}