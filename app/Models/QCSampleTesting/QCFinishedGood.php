<?php
namespace App\Models\QCSampleTesting;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class QCFinishedGood extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "qcfinishedgood";

    protected $fillable = [
       
        'userID',
        'status',
        'Forward_Status',
        'Approve_status',
        'Approve_Step',
        'Unit_Name',
        'Plant_Name',
        'Organization_Name',
        'BU_Name',
        'productionID',
        'batch_no',
        'SampleCollectedBy',
        'QCDate',
        'QCCode',
        'remarks',
    ];
  
}
