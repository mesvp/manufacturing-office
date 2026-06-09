<?php

namespace App\Models\GatePass;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatepass_Visitor_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "gatepass_visitor_name";

    protected $fillable = [

        'visitorID',
        'visitor_name',
        'person_to_meet',
        'department',
        'request_through',
        'reason_for_visit',
        'visitor_address',
        'visitor_in_time',
        'visitor_out_time',
        'vehicle',
        'vehicle_reg_no',
        'make_model',
    ];
}
