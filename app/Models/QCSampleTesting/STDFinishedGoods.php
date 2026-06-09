<?php
namespace App\Models\QCSampleTesting;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class STDFinishedGoods extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "STDFinishedGoods";

    protected $fillable = [
       
        'userID',
        'Organization',
        'Manufacturing_Unit',
        'category',
        'remarks',
    ];
  
}
