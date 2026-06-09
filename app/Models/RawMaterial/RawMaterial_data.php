<?php

namespace App\Models\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial_data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "rawmaterial_data";

    protected $fillable = [

        'RawMaterial_id',
        'Raw_Material',
        'OB',
        'Received_QTY',
        'UOM',
        'Balance_Stock',
        'rack_no',
        'sub_rack_no',
        'bin_no',
        'sub_bin_no',
        'rack_ob',
        'rack_cb',
        'bin_ob',
        'bin_cb',
    ];
}
