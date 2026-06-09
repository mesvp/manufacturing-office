<?php

namespace App\Http\Controllers\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RawMaterial\{RawMaterial, RawMaterial_data, RawMaterial_stock, RawMaterial_approve};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;

class RawMaterialController extends Controller
{
    public function AddRawMaterial(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Organization.*' => 'required',
                'Manufacturing_Unit.*' => 'required',
                'Godown_name.*' => 'required',
                'date.*' => 'required',
                'Raw_Material.*' => 'required',
                'OB.*' => 'required',
                'Received_QTY.*' => 'required',
                'UOM.*' => 'required',
                'rack_no.*' => 'required',
                'sub_rack_no.*' => 'required',
                'bin_no.*' => 'required',
                'sub_bin_no.*' => 'required',
                'rack_ob.*' => 'required',
                'rack_cb.*' => 'required',
                'bin_ob.*' => 'required',
                'bin_cb.*' => 'required',
            ]);
        }

        if ($request->edit != '') {
            $RawStock = RawMaterial_stock::find($request->edit);
        } else {
            $RawStock = new RawMaterial_stock;
            $RawStock->userID = auth()->user()->id;
            $RawStock->Forward_Status = 0;
        }
        $RawStock->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $RawStock->status = 0;
        } else {
            $RawStock->status = 1;
        }

        $RawStock->save();

        $res = $request->input();

        if ($request->edit != '') {
            $rawStockID = $request->edit;
        } else {
            $rawStockID = $RawStock->id;
        }

        if (isset($res['Organization']) && $res['Organization'] != '') {
            foreach ($res['Organization'] as $key => $val) {
                $raw_id = isset($res['raw_id'][$key]) ? $res['raw_id'][$key] : '';
                if ($raw_id != '') {
                    $rawmaterial = RawMaterial::where('id', $raw_id)->update(['Organization' => $res['Organization'][$key] ?? '', 'Manufacturing_Unit' => $res['Manufacturing_Unit'][$key] ?? '', 'Godown_name' => $res['Godown_name'][$key] ?? '']);
                } else {
                    $rawmaterial = new RawMaterial;
                    $rawmaterial->RawMaterial_stock_id = $rawStockID;
                    $rawmaterial->Organization = $res['Organization'][$key] ?? '';
                    $rawmaterial->Manufacturing_Unit = $res['Manufacturing_Unit'][$key] ?? '';
                    $rawmaterial->Godown_name = $res['Godown_name'][$key] ?? '';
                    $rawmaterial->date = date('Y-m-d') ?? '';

                    $rawmaterial->save();
                }

                if (isset($res['raw_id'][$key]) && $res['raw_id'][$key] != '') {
                    $rawMaterialID = $res['raw_id'][$key];
                } else {
                    $rawMaterialID = $rawmaterial->id;
                }

                if (isset($res['Raw_Material'][$key]) && $res['Raw_Material'][$key] != '') {
                    foreach ($res['Raw_Material'][$key] as $key1 => $val) {
                        $raw_data_id = isset($res['raw_data_id'][$key][$key1]) ? $res['raw_data_id'][$key][$key1] : '';
                        if ($raw_data_id != '') {
                            $Raw = RawMaterial_data::where('id', $raw_data_id)->update(['Raw_Material' => $res['Raw_Material'][$key][$key1] ?? '', 'OB' => $res['OB'][$key][$key1] ?? '', 'HSN_Code' => $res['HSN_Code'][$key][$key1] ?? '', 'Received_QTY' => $res['Received_QTY'][$key][$key1] ?? '', 'UOM' => $res['UOM'][$key][$key1] ?? '', 'Balance_Stock' => $res['Balance_Stock'][$key][$key1] ?? '', 'rack_no' => $res['rack_no'][$key][$key1] ?? '', 'sub_rack_no' => $res['sub_rack_no'][$key][$key1] ?? '', 'bin_no' => $res['bin_no'][$key][$key1] ?? '', 'sub_bin_no' => $res['sub_bin_no'][$key][$key1] ?? '', 'rack_ob' => $res['rack_ob'][$key][$key1] ?? '', 'rack_cb' => $res['rack_cb'][$key][$key1] ?? '', 'bin_ob' => $res['bin_ob'][$key][$key1] ?? '', 'bin_cb' => $res['bin_cb'][$key][$key1] ?? '']);
                        } else {
                            $Raw = new RawMaterial_data;
                            $Raw->RawMaterial_id = $rawMaterialID;
                            $Raw->Raw_Material = $res['Raw_Material'][$key][$key1] ?? '';
                            $Raw->HSN_Code = $res['HSN_Code'][$key][$key1] ?? '';
                            $Raw->OB = $res['OB'][$key][$key1] ?? '';
                            $Raw->Received_QTY = $res['Received_QTY'][$key][$key1] ?? '';
                            $Raw->UOM = $res['UOM'][$key][$key1] ?? '';
                            $Raw->Balance_Stock = $res['Balance_Stock'][$key][$key1] ?? '';
                            $Raw->rack_no = $res['rack_no'][$key][$key1] ?? '';
                            $Raw->sub_rack_no = $res['sub_rack_no'][$key][$key1] ?? '';
                            $Raw->bin_no = $res['bin_no'][$key][$key1] ?? '';
                            $Raw->sub_bin_no = $res['sub_bin_no'][$key][$key1] ?? '';
                            $Raw->rack_ob = $res['rack_ob'][$key][$key1] ?? '';
                            $Raw->rack_cb = $res['rack_cb'][$key][$key1] ?? '';
                            $Raw->bin_ob = $res['bin_ob'][$key][$key1] ?? '';
                            $Raw->bin_cb = $res['bin_cb'][$key][$key1] ?? '';

                            $Raw->save();
                        }
                        if (!isset($request->draft)) {
                            $UsedStatus = MaterialManagement_Add_Material::where('id', $res['Raw_Material'][$key][$key1])->update(['Used_Status_RM' => 1]);
                        }
                    }
                }
            }
        }

        if (!isset($request->draft)) {
            $Approve_step = RawMaterial_stock::where('id', $rawStockID)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = RawMaterial_stock::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = RawMaterial_stock::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = RawMaterial_approve::where('RawMaterial_stock__id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new RawMaterial_approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->RawMaterial_stock__id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }


        return redirect('RawMaterial/RawMaterialList')->with('success', 'Added Successfully....');
    }
}
