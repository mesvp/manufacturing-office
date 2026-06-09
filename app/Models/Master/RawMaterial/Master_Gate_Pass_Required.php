<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Gate_Pass_Required extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_gate_pass_required";

    protected $fillable = [

        'Gate_Pass_Required',
    ];
}