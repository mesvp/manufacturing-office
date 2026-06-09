<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Raw_Material_Detail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_raw_material_details";

}