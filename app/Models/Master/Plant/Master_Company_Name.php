<?php
namespace App\Models\Master\Plant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Company_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_company_name";

    protected $fillable = [

        'userID',
        'Company_Name',
    ];
    static function all_con()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['Company_Name'];
        }
        return $data;
    }
}