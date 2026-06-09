<?php
namespace App\Models\Master;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Module_Prj_Assignment extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "module_prj_assignment";

    protected $fillable = [

        'unit_name',
    ];
    static function all_bu()
    {
        $data=[];
        foreach(self::all()->toArray() as $value)
        {
            $data[$value['id']]=$value['unit_name'];
        }
        return $data;
    }
  
}
