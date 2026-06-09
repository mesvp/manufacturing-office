<?php
namespace App\Models\SampleFreeGood;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SampleFreeGood extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "samplefreegood";

    protected $fillable = [
       
        'userID',
        'remarks',
    ];
  
}
