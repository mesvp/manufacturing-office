<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department_Assign extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "department_assign";

    protected $fillable = [

        'departments',
    ];
}
