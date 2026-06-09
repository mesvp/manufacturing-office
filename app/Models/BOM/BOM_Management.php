<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Management extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_management";

    protected $fillable = [
       
        'BOM_ID',
        'Management_Expenses',
        'Management_Expenses_Amount',
    ];
  
}
