<?php
namespace App\Models\CertificateDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CertificateDetails_data extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "CertificateDetails_data";

    protected $fillable = [
       
        'CertificateDetails_id',
        'Certificate_Name',
        'Certificate_Number',
        'Organization',
        'Manufacturing_Unit',
        'Plant_Name',
        'Scope_For',
        'Attachment',
        'Date',
        'Registration_Date',
        'Expiry_Date',
        'Validity',
        'Authorisor',
        'Status',
    ];
  
}
