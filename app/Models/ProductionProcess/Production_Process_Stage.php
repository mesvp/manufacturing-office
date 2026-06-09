<?php
namespace App\Models\ProductionProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Production_Process_Stage extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_process_stage";

    protected $fillable = [
       
        'Production_Process_Id',
        'Process_Stage',
    ];
  
}
