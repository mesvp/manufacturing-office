<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Sub_Bin_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Sub_Bin_No";

    protected $fillable = [

        'Sub_Bin_No',
    ];
}