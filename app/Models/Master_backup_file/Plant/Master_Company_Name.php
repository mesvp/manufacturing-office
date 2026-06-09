<?php
namespace App\Models\Master\Plant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Company_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Company_Name";

    protected $fillable = [

        'userID',
        'Company_Name',
    ];
}