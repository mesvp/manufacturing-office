<?php

namespace App\Models\Gatepass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\FactoryCreater\prj_organisation;

class OutGatepassMaterials extends Model
{
    use HasFactory;

    public function organizationDatas(): HasOne
    {
        return $this->hasOne(prj_organisation::class, 'id', 'org_id');
    }
}
