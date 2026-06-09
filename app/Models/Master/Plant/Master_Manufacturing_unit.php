<?php
namespace App\Models\Master\Plant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Manufacturing_unit extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_manufacturing_unit";

    protected $fillable = [

        'Manufacturing_unit',
    ];
    static function all_mu()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['Manufacturing_unit'];
        }
        return $data;
    }
}
