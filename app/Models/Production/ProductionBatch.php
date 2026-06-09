<?php
namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ProductionBatch extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "production_batch";

    protected $fillable = [
        'productionID',
        'batch_no',
        'sl_no',
       
    ];
  
}
