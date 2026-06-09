<?php
namespace App\Models\QCSampleTesting;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class STDFinishedGoods_data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "STDFinishedGoods_data";

    protected $fillable = [
       
        'STDFinishedGoods_id',
        'Product',
        'Sub_Product',
        'Sub_Sub_Product',
        'Sample_Collected',
        'Batch_No',
        'SL_No',
        'Result',
    ];
  
}
