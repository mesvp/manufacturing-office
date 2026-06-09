<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlantStock extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = "plant_stock";

    protected $fillable = [
        'plantID',
        'materialID',
        'stock',
    ];
    public static function stock_vendor($plant,$materialID,$Unit,$stock,$type=1)
    {
        //dd($materialID);
        $data=self::where(['plantID'=>$plant,'materialID'=>$materialID,'Manufacturing_Unit'=>$Unit,'type'=>1])->get()->first();
        //dd($data);
        if($type==1)
        {
            $add=$data->stock+$stock;
        }
        else{
            // if($data->stock>=$stock)
            // {
                $add=$data->stock-$stock;
            // }
            // else{
            //     return false;
            // }

        }
        $data=self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>1,'Manufacturing_Unit'=>$Unit])->update(['stock'=>$add]);
        if($data)
        {
            return true;
        }
        else{
            return false;
        }

    }
    public static function stock($plant,$materialID,$stock,$type=1)
    {
         //dd($materialID);
        $data=self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>0])->get()->first();
        //dd($data);
        if($type==1)
        {
            $add=$data->stock+$stock;
        }
        else{
            // if($data->stock>=$stock)
            // {
                $add=$data->stock-$stock;
            // }
            // else{
            //     return false;
            // }

        }
        $data=self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>0])->update(['stock'=>$add]);
        if($data)
        {
            return true;
        }
        else{
            return false;
        }

    }
    public static function get_stock($plant,$materialID)
    {
        return self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>0])->first();
    }
    public static function get_stock_vendor($plant,$materialID,$Unit,$type=0)
    {
        if($type==0)
        {
            return self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>1,'Manufacturing_Unit'=>$Unit])->first();
        }
        else{
             $stock=self::where(['plantID'=>$plant,'materialID'=>$materialID,'type'=>1,'Manufacturing_Unit'=>$Unit])->first();
             return $stock->stock??0;
        }
    }


}
