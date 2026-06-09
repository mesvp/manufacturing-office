<?php
namespace App\Models\ThirdParty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThirdParty_RawMaterial extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "ThirdParty_RawMaterial";

    protected $fillable = [

        'userID',
        'Vender_Name',
        'Job_Order_no',
        'Organization',
        'Manufacturing_Unit',
        'BU',
        'Plant_Name',
        'remarks',
    ];
}