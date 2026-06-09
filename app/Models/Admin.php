<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $guard='mstr_emp';
    public $table = "mstr_emp";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'uname',
        'role',        
        'upass',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'upass',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //  static function all_admin()
    // {
    //     $data=[];
    //     foreach(self::where('role',1)->get()->toArray() as $value)
    //     {
    //         $data[$value['id']]=$value['fullname'];
    //     }
    //     return $data;
    // }
    
    static function all_admin()
    {
        $data = [];

        $users = self::all();

        foreach ($users as $user) {
            if ($user->role == 1) {
                $data[$user->id] = $user->fullname;
            } else {
                $data[$user->id] = $user->fullname;
            }
        }

        return $data;
    }
}
