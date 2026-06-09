<?php
namespace App\Models\Master\Plant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Work_Order_Status extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Work_Order_Status";

    protected $fillable = [

        'userID',
        'Work_Order_Status',
    ];
}