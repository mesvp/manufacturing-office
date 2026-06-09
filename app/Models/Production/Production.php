<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production";

    protected $fillable = [
        'userID',
        'status',
        'Forward_Status',
        'Approve_status',
        'Approve_Step',
        'Unit_Name',
        'Plant_Name',
        'Organization_Name',
        'BU_Name',
        'Shift',
        'Production_Date',
        'Raw_Material',
        'remarks',
        'UOM',
        'Rate',
        'Quantity',
        'Total_amount',
    ];
  
}
