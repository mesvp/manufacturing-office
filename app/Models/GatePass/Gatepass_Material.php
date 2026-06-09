<?php

namespace App\Models\GatePass;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatepass_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "gatepass_materials";

    protected $fillable = [

        'userID',
        'request_no',
        'request_by',
        'gate_pass_no',
        'request_date',
        'request_time',
        'vehicle_no',
        'vehicle_weight_with_mat',
        'invoice_challan_no',
        'builty_no',
        'starting_location',
        'iteam_description',
        'driver_name',
        'driver_number',
        'vehicle_in_time',
        'contact_person',
        'vehicle_out_time',
        'vehicle_weight_without_mat',
        'remarks',
    ];
}
