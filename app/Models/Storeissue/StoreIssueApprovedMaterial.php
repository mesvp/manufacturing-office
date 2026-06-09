<?php
namespace App\Models\Storeissue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StoreIssueApprovedMaterial extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  
    
    public $table = "store_issue_approved_material";

    protected $fillable = [
        'Store_Requistion_id',
        'userID',
        'Store_Requistion_material_id',
        'recived_ApproverID',
        'issueQTY',
        'status',
    ];
  
}