<?php
namespace App\Models\PPFinishedGood;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PPFinishedGood_data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "ppfinishedgood_data";

    protected $fillable = [
       
        'PPFinishedGood_id',
        'Organization',
        'Manufacturing_Unit',
        'Plant_name',
        'category',
        'Product',
        'Sub_Product',
        'Sub_Sub_Product',
        'Color',
        'For_Primary',
        'QTY',
        'UOM',
        'Per_Day',
        'Per_Shift',
    ];
  
}
