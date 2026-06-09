<?php
namespace App\Models\Master\Plant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Quality_Check extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_quality_check";

    protected $fillable = [

        'quality_check',
    ];
}