<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductionData extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_data";

    protected $fillable = [
       'productionID',
       'RawMaterialName',
       'RawMaterial_id',
       'PlantStock',
       'UMO',
       'ConsumtionQty',
       'ScarpQty',
       'OtherQty',
       'TotalQty',
    ];
  
}
