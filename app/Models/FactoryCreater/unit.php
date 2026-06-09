<?php
namespace App\Models\FactoryCreater;

$keyval = env('DB_DATABASE_SECOND');

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class unitname extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "prj_material";
     
}
