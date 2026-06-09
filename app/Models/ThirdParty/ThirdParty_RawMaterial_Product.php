<?php
namespace App\Models\ThirdParty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThirdParty_RawMaterial_Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "ThirdParty_RawMaterial_Product";

    protected $fillable = [

        'ThirdParty_RawMaterial_id',
        'Product_Name',
        'Sub_Product',
        'Sub_Sub_Product',
        'QTY',
        'UOM',
        'Time_Taking',
        'Total_Time',
    ];
}