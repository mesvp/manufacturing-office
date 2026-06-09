<?php

namespace App\Models\Gatepass;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\FactoryCreater\Factory_Uom;

class InGatepassItemDetails extends Model
{
    use HasFactory;

    public function uomDatas(): HasOne
    {
        return $this->hasOne(Factory_Uom::class, 'id', 'uom_id');
    }
}
