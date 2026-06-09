<?php
namespace App\Models\Master\RawMaterial;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Godown_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_godown_name";

    protected $fillable = [

        'Godown_Name',
    ];
    static function all_godownname()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['Godown_Name'];
        }
        return $data;
    }
}