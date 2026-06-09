<?php
namespace App\Models\FactoryCreater;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactoryProjectApproval extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;  
    public $table = "factory_project_approval";
  
}
