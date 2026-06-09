<?php

namespace App\Models\MaterialManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialManagement_Add_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $table = "materialmanagement_add_material";

    protected $fillable = [

        'userID',
        'Material_id',
        'Material_Name',
        'HSN_Code',
        'UOM',
        'last_purchase_price',
        'last_purchase_date',
        'last_purchase_vndr_name',
        'remarks',
    ];
}
