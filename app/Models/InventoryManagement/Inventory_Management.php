<?php
namespace App\Models\InventoryManagement;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Inventory_Management extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "inventory_management";

    protected $fillable = [
       
        'userID',
        'remarks',
        'status', 
        'Forward_Status', 
        'Approve_status', 
        'Approve_Step', 
        'Unit_Name',
        'Plant_Name',
        'Organization_Name',
        'BU_Name',
        'batch_no',
        'QCCode',
    ];
  
}
