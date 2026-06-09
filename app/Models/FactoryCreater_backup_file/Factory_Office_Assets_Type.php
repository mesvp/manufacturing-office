<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Office_Assets_Type extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_office_assets_types";

    protected $fillable = [
       
        'factory_office_assets_id',
        'Asset_Type',
        'Asset_Name',
        'Asset_SL_No',
        'Date_Of_Purchase',
        'Supplier_Name',
        'invoice_No',
        'QTY',
        'Organization',
        'Use_By',
        'Use_In',
        'Location',
    ];
  
}
