<?php
namespace App\Models\ThirdParty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ThirdParty_FinishedGoods extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "ThirdParty_FinishedGoods";

    protected $fillable = [

        'userID',
        'Vender_Name',
        'Job_Order_no',
        'Organization',
        'Manufacturing_Unit',
        'BU',
        'Plant_Name',
        'Product_Name',
        'Sub_Product',
        'Sub_Sub_Product',
        'QTY',
        'QTY_Received',
        'QTY_Pending',
        'UOM',
        'Time_Taking',
        'Total_Time',
        'Raw_Material_Handover',
        'Finished_Goods_Handover',
        'QC_Check',
        'Store_In',
        'Shelf_No',
        'Sub_Shelf_No',
        'Sub_Sub_Shelf_No',
        'Update_By',
        'Update_In',
        'remarks',
    ];
}