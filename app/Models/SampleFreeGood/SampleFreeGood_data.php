<?php
namespace App\Models\SampleFreeGood;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SampleFreeGood_data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "samplefreegood_data";

    protected $fillable = [
       
        'SampleFreeGood_id',
        'Organization',
        'Manufacturing_Unit',
        'Godawn_Factory',
        'Name',
        'Product',
        'Reason_For',
        'Customer_Name',
        'QTY',
        'UOM',
        'Date',
        'Batch_No',
        'Sl_No',
        'Godown_Shelf_No',
    ];
  
}
