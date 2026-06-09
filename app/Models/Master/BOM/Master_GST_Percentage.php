<?php
namespace App\Models\Master\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_GST_Percentage extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "master_gst_percentage";

    protected $fillable = [       
        'GST_Percentage',
    ];
  
}