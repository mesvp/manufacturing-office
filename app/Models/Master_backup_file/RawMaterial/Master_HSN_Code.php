<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_HSN_Code extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_HSN_Code";

    protected $fillable = [

        'HSN_Code',
    ];
}