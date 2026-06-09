<?php

namespace App\Models\GatePass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class GatepassSlno extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "gatepass_slno";
}
