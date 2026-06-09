<?php
namespace App\Models\Storeissue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Store_issue extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "store_issue";

    protected $fillable = [
       
        'userID',
        'Issued_No',
        'Request_No',
        'Request_by',
        'date',
        'Work_Order_No',
        'remarks',
    ];
  
}