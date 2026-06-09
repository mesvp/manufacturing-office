<?php
namespace App\Models\ProductionProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production_Process_Stage_Data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_process_stage_data";

    protected $fillable = [
       
        'Production_Process_Stage_Id',
        'Process_Name',
        'Material_Use',
        'Output',
        'Description_Second',
    ];
  
}
