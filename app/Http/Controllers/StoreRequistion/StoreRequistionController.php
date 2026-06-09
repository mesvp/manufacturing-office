<?php

namespace App\Http\Controllers\StoreRequistion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreRequistion\{Store_Requistion, Store_Requistion_Material, Store_Requistion_approve};

class StoreRequistionController extends Controller
{
    public function AddStoreRequistion(Request $request)
    {
        //return $request->all();
        if (!isset($request->draft)) {
            $required = [
                'Organization_Name' => 'required',
                'Manufacturing_Unit' => 'required',
                'Plant_Name' => 'required',
                'Godown_Name' => 'required',
            ];

            $request->validate($required);
        }

        if ($request->edit != '') {
            $sample = Store_Requistion::find($request->edit);
        } else {
            $sample = new Store_Requistion;
            $sample->userID = auth()->user()->id;
            $sample->status = 0;
            $sample->Forward_Status = 0;
        }
        $sample->Work_Order_No = $request->Work_Order_No;
        $sample->Organization_Name = $request->Organization_id;
        $sample->Manufacturing_Unit = $request->Manufacturing_Unit;
        $sample->Plant_Name = $request->Plant_Name;
        $sample->Godown_Name = $request->Godown_Name;
        $sample->Raw_Material = $request->Raw_Material;
        $sample->HSN_Code = $request->HSN_Code;
        $sample->UOM = $request->UOM;
        $sample->remarks = $request->remarks;
        $sample->req_type = $request->req_type;
        if (!isset($request->draft)) {
            $sample->status = 0;
        } else {
            $sample->status = 1;
        }

        $sample->save();

        $sample->Request_No = 'SR' . str_pad($sample->id, 4, '0', STR_PAD_LEFT);

        $sample->save();

        $DeletePrivious = Store_Requistion_Material::where('Store_Requistion_id', $sample->id)->delete();

        $res = $request->input();

        if($request->req_type == "Normal"){
            if (isset($res['MaterialID']) && $res['MaterialID'] != '') {
                foreach ($res['MaterialID'] as $key => $val) {
    
                    $Stage = new Store_Requistion_Material;
                    $Stage->Store_Requistion_id = $sample->id;
                    $Stage->Material_Name = $res['Material_Name'][$key] ?? '';
                    $Stage->Material_id = $res['Material_id'][$key] ?? '';
                    $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                    $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                    $Stage->QTY = $res['QTY'][$key] ?? '';
                    $Stage->save();
                }
            }
        }else{
            foreach ($res['Material_id'] as $key => $val) {
    
                $Stage = new Store_Requistion_Material;
                $Stage->Store_Requistion_id = $sample->id;
                $Stage->Material_Name = $res['Material_Name'][$key] ?? '';
                $Stage->Material_id = $res['Material_id'][$key] ?? '';
                $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                $Stage->QTY = $res['QTY'][$key] ?? '';
                $Stage->save();
            }
        }
      

        if (!isset($request->draft)) {
            $Approve_step = Store_Requistion::where('id', $sample->id)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = Store_Requistion::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = Store_Requistion::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = Store_Requistion_approve::where('Store_Requistion_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new Store_Requistion_approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->Store_Requistion_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('StoreRequistion/StoreRequistionList')->with('success', 'Added Successfully....');
    }
}
