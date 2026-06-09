<?php
namespace App\Models\QCSampleTesting;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class QCFinishedGoodResult extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "qcfinishedgoodresult";

    protected $fillable = [
       
        'QCFinishedGoodID',
        'productionID',
        'production_batchID',
        'batch_no',
        'sl_no',
        'result',
        'remark',
    ];
  
}
