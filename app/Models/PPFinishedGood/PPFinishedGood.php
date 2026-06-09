<?php
namespace App\Models\PPFinishedGood;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PPFinishedGood extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "ppfinishedgood";

    protected $fillable = [
       
        'userID',
        'Make_To',
        'remarks',
    ];

    public function Data()
    {
        return $this->hasOne(PPFinishedGood_data::class, 'PPFinishedGood_id');
    }

  
}
