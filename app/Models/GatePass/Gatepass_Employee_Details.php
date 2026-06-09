<?php

namespace App\Models\Gatepass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;   
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatepass_Employee_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "gatepass_employee_details";
    
}
