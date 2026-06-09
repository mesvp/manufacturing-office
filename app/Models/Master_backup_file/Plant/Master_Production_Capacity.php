<?php
namespace App\Models\Master\Plant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Production_Capacity extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Production_Capacity";

    protected $fillable = [

        'Production_Capacity',
    ];
}