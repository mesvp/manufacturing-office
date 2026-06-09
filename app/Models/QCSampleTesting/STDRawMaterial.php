<?php
namespace App\Models\QCSampleTesting;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class STDRawMaterial extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "STDRawMaterial";

    protected $fillable = [
       
        'userID',
        'Invoice_no',
        'PO_NO',
        'Material_Code',
        'Material_Name',
        'Material_Type',
        'HNS_Code',
        'QC_Status',
        'Parameter_one',
        'result_one',
        'remarks_one',
        'Parameter_two',
        'Result_two',
        'remarks_two',
        'remarks',
    ];
  
}
