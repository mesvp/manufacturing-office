<?php
namespace App\Models\Master\Plant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Customer_Name extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "master_customer_name";

    protected $fillable = [

        'userID',
        'Customer_Name',
    ];
    static function all_cn()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['Customer_Name'];
        }
        return $data;
    }
}