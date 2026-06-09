<?php

namespace App\Models\GatePass;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatepass_Visitor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "gatepass_visitors";

    protected $fillable = [

        'userID',
        'request_no',
        'request_by',
        'gate_pass_no',
        'request_date',
        'request_time',
        'remarks',
    ];
}
