<?php

namespace App\Models\Gatepass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Forwarded_Data_Gatepass extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "forwarded_data_gatepass";
}