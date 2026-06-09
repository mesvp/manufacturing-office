<?php
namespace App\Models\Master\Plant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_BU extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_bu";

    protected $fillable = [

        'BU',
    ];
    static function all_bu()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['BU'];
        }
        return $data;
    }
}
