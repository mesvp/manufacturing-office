<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forwarded_Data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "forwarded_data";

    protected $fillable = [

        'userID',
        'Forward_To_id',
        'DepartmentID',
        'DataID',
        'status',
        'remarks',
    ];
}
