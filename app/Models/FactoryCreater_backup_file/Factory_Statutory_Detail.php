<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Statutory_Detail extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  

    public $table = "factory_statutory_details";

    protected $fillable = [
       
        'factory_id',
        'gst_no',
        'gst_in_certificate_attachement',
        'pan',
        'pan_attachement',
        'factory_license_no',
        'factory_license_attachement',
        'labour_license_no',
        'labour_license_attachement',
        'pollution_certificate_no',
        'pollution_certificate_attachement',
        'Remarks',
    ];
  
}
