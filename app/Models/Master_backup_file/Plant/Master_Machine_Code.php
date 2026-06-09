<?php
namespace App\Models\Master\Plant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Machine_Code extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Machine_Code";

    protected $fillable = [

        'Machine_Name_id',
        'Machine_Code',
    ];
}