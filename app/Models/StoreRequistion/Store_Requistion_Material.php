<?php
namespace App\Models\StoreRequistion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Store_Requistion_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  

    public $table = "store_requistion_material";

    protected $fillable = [
       
        'Store_Requistion_id',
        'UOM_Second',
        'Material_Name',
        'HSN_Code_Second',
        'QTY',
    ];
  
}
