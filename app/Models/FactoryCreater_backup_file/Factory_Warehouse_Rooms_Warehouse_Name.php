<?php

namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Warehouse_Rooms_Warehouse_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "factory_warehouse_rooms_warehouse_names";

    protected $fillable = [

        'factory_warehouse_rooms_id',
        'Warehouse_Name',
        'Count',
        'Warehouse_Type',
    ];
}
