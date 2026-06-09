<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Warehouse_Room extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_warehouse_rooms";

    protected $fillable = [
       
        'factory_id',
        'Total_Warehouse',
        'Total_Room',
        'Remark',
    ];
  
}
