<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Material extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_material";

    protected $fillable = [
       
        'BOM_ID',
        'Material',
        'Code',
        'UOM',
        'Material_QTY',
        'Scarp_QTY',
        'Total_QTY',
        'Basic_Amount_unit',
        'Total_Basic_Amount',
        'GST_Percentage',
        'GST_Value',
        'Total_Amount',
    ];
  
}
