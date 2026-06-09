<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Factory_Statutory_Detail_Other extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "factory_statutory_detail_others";

    protected $fillable = [
       
        'factory_statutory_details_id',
        'add_field_manually',
        'add_field_attachement_manually',
       
    ];
  
}
