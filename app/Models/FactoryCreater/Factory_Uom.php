<?php
namespace App\Models\FactoryCreater;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory_Uom extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    
    public $table = "factory_uoms";

    protected $fillable = [
       
        'UOMs',
    ];
    static function all_uom()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['UOMs'];
        }
        return $data;
    }
  
}
