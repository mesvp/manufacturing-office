<?php
namespace App\Models\CertificateDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CertificateDetails extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "CertificateDetails";

    protected $fillable = [
       
        'userID',
        'remarks',
    ];
  
}
