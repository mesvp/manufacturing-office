<?php

namespace App\Http\Controllers\PPFinishedGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PPFinishedGood\{PPFinishedGood, PPFinishedGood_data, PPFinishedGood_Approve};
use Session;

class PPFinishedGoodController extends Controller
{
    public function AddPPFinishedGood(Request $request)
    {
        //return $request->all();
        if (!isset($request->draft)) {
            $request->validate([
                'Make_To' => 'required',
                'Organization.*' => 'required',
                'Manufacturing_Unit.*' => 'required',
                'Plant_name.*' => 'required',
                'category.*' => 'required',
                'Product.*' => 'required',
                'Sub_Product.*' => 'required',
                'Sub_Sub_Product.*' => 'required',
                'Color.*' => 'required',
                'For_Primary.*' => 'required',
                'QTY.*' => 'required',
                'Raw_Material.*' => 'required',
                'HSN_Code.*' => 'required',
                'UOM.*' => 'required',
                'Per_Day.*' => 'required',
                'Per_Shift.*' => 'required',
            ]);
        }

        if ($request->edit != '') {
            $PP = PPFinishedGood::find($request->edit);
        } else {
            $PP = new PPFinishedGood;
            $PP->userID = auth()->user()->id;
            $PP->Forward_Status = 0;
        }
        $PP->Make_To = $request->Make_To;
        $PP->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $PP->status = 0;
        } else {
            $PP->status = 1;
        }

        $PP->save();

        $PP->Planing_Batch_No = 'PBN' . str_pad($PP->id, 4, '0', STR_PAD_LEFT);
        $PP->save();

        $res = $request->input();

        if ($request->edit != '') {
            $PPID = $request->edit;
        } else {
            $PPID = $PP->id;
        }

        if (isset($res['Organization']) && $res['Organization'] != '') {
            foreach ($res['Organization'] as $key => $val) {
                $PP_Data_Id = isset($res['PP_Data_Id'][$key]) ? $res['PP_Data_Id'][$key] : '';
                if ($PP_Data_Id != '') {
                    $PPDATA = PPFinishedGood_data::where('id', $PP_Data_Id)->update(['Organization' => $res['Organization_id'][$key] ?? 0, 'Manufacturing_Unit' => $res['Manufacturing_Unit'][$key] ?? 0, 'Plant_name' => $res['Plant_name'][$key] ?? 0, 'category' => $res['category'][$key] ?? 0, 'Product' => $res['Product'][$key] ?? 0, 'Sub_Product' => $res['Sub_Product'][$key] ?? 0, 'Sub_Sub_Product' => $res['Sub_Sub_Product'][$key] ?? 0, 'Color' => $res['Color'][$key] ?? '', 'For_Primary' => $res['For_Primary'][$key] ?? '', 'QTY' => $res['QTY'][$key] ?? '', 'UOM' => $res['UOM'][$key] ?? 0, 'Raw_Material' => $res['Raw_Material'][$key] ?? '', 'HSN_Code' => $res['HSN_Code'][$key] ?? '', 'Per_Day' => $res['Per_Day'][$key] ?? '', 'Per_Shift' => $res['Per_Shift'][$key] ?? '']);
                } else {
                    $PPDATA = new PPFinishedGood_data;
                    $PPDATA->PPFinishedGood_id = $PPID;
                    $PPDATA->Organization = $res['Organization_id'][$key] ?? 0;
                    $PPDATA->Manufacturing_Unit = $res['Manufacturing_Unit'][$key] ?? 0;
                    $PPDATA->Plant_name = $res['Plant_name'][$key] ?? 0;
                    $PPDATA->category = $res['category'][$key] ?? 0;
                    $PPDATA->Product = $res['Product'][$key] ?? 0;
                    $PPDATA->Sub_Product = $res['Sub_Product'][$key] ?? 0;
                    $PPDATA->Sub_Sub_Product = $res['Sub_Sub_Product'][$key] ?? 0;
                    $PPDATA->Color = $res['Color'][$key] ?? '';
                    $PPDATA->For_Primary = $res['For_Primary'][$key] ?? '';
                    $PPDATA->QTY = $res['QTY'][$key] ?? '';
                    $PPDATA->Raw_Material = $res['Raw_Material'][$key] ?? '';
                    $PPDATA->HSN_Code = $res['HSN_Code'][$key] ?? '';
                    $PPDATA->UOM = $res['UOM'][$key] ?? 0;
                    $PPDATA->Per_Day = $res['Per_Day'][$key] ?? '';
                    $PPDATA->Per_Shift = $res['Per_Shift'][$key] ?? '';

                    $PPDATA->save();
                }
            }
        }

        if (!isset($request->draft)) {
            $Approve_step = PPFinishedGood::where('id', $PPID)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = PPFinishedGood::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = PPFinishedGood::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = PPFinishedGood_Approve::where('PPFinishedGood_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new PPFinishedGood_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->PPFinishedGood_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('PPFinishedGood/PPFinishedGoodList')->with('success', 'Added Successfully....');
    }
}
