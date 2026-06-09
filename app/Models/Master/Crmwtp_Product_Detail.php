<?php
namespace App\Models\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Crmwtp_Product_Detail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "crmwtp_product_details";
  
}
