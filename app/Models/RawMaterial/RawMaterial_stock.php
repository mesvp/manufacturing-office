<?php

namespace App\Models\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial_stock extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "rawmaterial_stock";

    protected $fillable = [

        'userID',
        'remarks',
    ];

    public function RawMaterial()
    {
        return $this->hasMany(RawMaterial::class, 'RawMaterial_stock_id')
            ->selectRaw('RawMaterial_stock_id, COUNT(*) as RawMaterial')
            ->groupBy('RawMaterial_stock_id');
    }

    public function RawMaterialData()
    {
        return $this->hasMany(RawMaterial_data::class, 'RawMaterial_id')
            ->selectRaw('RawMaterial_id, COUNT(*) as RawMaterialData')
            ->groupBy('RawMaterial_id');
    }
}
