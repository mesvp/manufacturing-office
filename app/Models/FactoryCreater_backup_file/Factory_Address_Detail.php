<?php

namespace App\Models\FactoryCreater;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Address_Detail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "factory_address_details";

    protected $fillable = [

        'organization',
        'name_of_unit',
        'country',
        'state',
        'district',
        'pincode',
        'remarks',
    ];

    public function plantsCount()
    {
        return $this->hasMany(Factory_Plant_Machinery::class, 'factory_id')
            ->selectRaw('factory_id, COUNT(*) as plant_count')
            ->groupBy('factory_id');
    }

    public function statutory()
    {
        return $this->hasOne(Factory_Statutory_Detail::class, 'factory_id');
    }

    public function landbuilding()
    {
        return $this->hasOne(Factory_Land_Building::class, 'factory_id');
    }

    public function WareHouseRoom()
    {
        return $this->hasOne(Factory_Warehouse_Room::class, 'factory_id');
    }

    public function store()
    {
        return $this->hasOne(Factory_Store::class, 'factory_id');
    }
}
