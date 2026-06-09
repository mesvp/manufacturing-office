<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Received_QTY extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "Master_Received_QTY";

    protected $fillable = [

        'Received_QTY',
    ];
}