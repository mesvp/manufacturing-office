<?php

namespace App\Models\GatePass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany};
use App\Models\GatePass\{OutGatepassMaterials, InGatepassAttachment};
use App\Models\FactoryCreater\prj_organisation;

class InGatepassMaterials extends Model
{
    use HasFactory;

    public function inGatepassAttachs(): HasMany
    {
        return $this->hasMany(InGatepassAttachment::class, 'in_gatepass_req_id', 'id');
    }

    public function outGatepassDatas(): HasOne
    {
        return $this->hasOne(OutGatepassMaterials::class, 'gate_pass_no', 'gate_pass_no');
    }

    public function organizationDatas(): HasOne
    {
        return $this->hasOne(prj_organisation::class, 'id', 'org_id');
    }
}

// InGatepassMaterials.php


