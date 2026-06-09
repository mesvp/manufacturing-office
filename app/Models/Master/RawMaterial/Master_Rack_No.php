<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Rack_No extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_rack_no";

    protected $fillable = [

        'Rack_No',
    ];
}