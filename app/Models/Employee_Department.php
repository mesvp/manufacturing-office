<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee_Department extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "employee_department";

    protected $fillable = [
        'userID',
        'Departments',
    ];
}
