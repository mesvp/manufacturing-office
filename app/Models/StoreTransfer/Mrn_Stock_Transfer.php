<?php
namespace App\Models\StoreTransfer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mrn_Stock_Transfer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "mrn_stock_transfer";
  
}