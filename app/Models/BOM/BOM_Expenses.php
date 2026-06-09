<?php
namespace App\Models\BOM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class BOM_Expenses extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "bom_expenses";

    protected $fillable = [
       
        'BOM_ID',
        'Other_Expenses',
        'Other_Expenses_Amount',
    ];
  
}
