<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Type_Details extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    public $table = "master_type_dtls";

    static function vehicle_type()
    {
        $data=[];
        foreach(self::where('parent_name','vehicle_type')->toArray() as $value)
        {
            $data[$value['id']]=$value['mstr_type_value'];
        }
        return $data;
    }

}
