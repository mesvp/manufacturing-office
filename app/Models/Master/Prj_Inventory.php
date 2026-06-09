<?php
namespace App\Models\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Prj_Inventory extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "prj_inventory";

    protected $fillable = [

        'inventory_name',
    ];
    static function all_godownname()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['inventory_name'];
        }
        return $data;
    }

}
