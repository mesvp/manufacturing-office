<?php

namespace App\Http\Controllers\SampleFreeGood;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampleFreeGood\{SampleFreeGood, SampleFreeGood_data, SampleFreeGoodApprove};
use Session;
use App\Models\PlantStock;
use App\Models\Master\RawMaterial\Master_Raw_Material;

class SampleFreeGoodController extends Controller
{
    public function recheck($request)
    {
        $approve = new SampleFreeGoodApprove;
        $approve->userID = auth()->user()->id;
        $approve->role = 'Inputer';
        $approve->SampleFreeGoodID = $request->edit;
        $approve->status = 1;
        $approve->action = 'Checked';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();
    }
    public function AddSampleFreeGood(Request $request)
    {
        //dd($request->all());
        $required = [
            'BU' => 'required',
            'Organization_Name' => 'required',
            'Manufacturing_Unit' => 'required',
            //'Plant_Name' => 'required',
            //'Godown_Name' => 'required',
            'Raw_Material' => 'required',
            'UOM' => 'required',
            'Quantity' => 'required',
            'date' => 'required',
            'Customer_Name' => 'required',
            'Customer_Address' => 'required',
            'Customer_Phone' => 'required',
            'Company_Name' => 'required',
            'reason' => 'required',
            //'remarks' => 'required',
            'batch_no.*' => 'required',
            'sl_no.*' => 'required',
        ];
        if ($request->Plant_Name != '') $required['Plant_Name'] = 'required';
        if ($request->Godown_Name != '') $required['Godown_Name'] = 'required';
        $request->validate($required);
        if ($request->Plant_Name != '') {
            PlantStock::stock_vendor($request->Plant_Name,$request->Raw_Material,$request->Manufacturing_Unit,$request->Quantity,0);
        } else {
            Master_Raw_Material::stock($request->Organization_Name,$request->Godown_Name,$request->Raw_Material,$request->Quantity,0);
        }

       

        if ($request->edit != '') {
            $sample = SampleFreeGood::where('id', $request->edit)->first();
            $sample->Approve_status = null;
            //Production::where('id', $request->edit)->update(['Approve_status' => null]);
            SampleFreeGoodApprove::where('SampleFreeGoodID', $request->edit)->where('status', 1)->update(['status' => 0]);
            $this->recheck($request);
        } else {
            $sample = new SampleFreeGood;
            $sample->userID = auth()->user()->id;
            if (!isset($request->draft)) {
                $sample->Approve_Step = 1;
            }

        }
        if (!isset($request->draft)) {
            $sample->status = 0;
        } else {
            $sample->status = 1;
        }
        $sample->remarks = $request->remarks;
        $sample->BU = $request->BU;
        $sample->Organization_Name = $request->Organization_Name;
        $sample->Manufacturing_Unit = $request->Manufacturing_Unit;
        $sample->Plant_Name = $request->Plant_Name;
        $sample->Godown_Name = $request->Godown_Name;
        $sample->Raw_Material = $request->Raw_Material;
        $sample->UOM = $request->UOM;
        $sample->Quantity = $request->Quantity;
        $sample->Date = $request->date;
        $sample->CustomerName = $request->Customer_Name;
        $sample->CustomerAddress = $request->Customer_Address;
        $sample->CustomerPhone = $request->Customer_Phone;
        $sample->CompanyName = $request->Company_Name;
        $sample->Reason = $request->reason;
        $sample->save();

        $res = $request->input();
        if ($request->edit != '') {
            SampleFreeGood_data::where('SampleFreeGood_id', $request->edit)->delete();
        }
        if(!empty($request->Godown_Name))
        {
            if (isset($res['sl_no'])) {
                foreach ($res['sl_no'] as $key =>$sl_no) {
               
                            $sample_data = new SampleFreeGood_data;
                            $sample_data->SampleFreeGood_id = $sample->id;
                            $sample_data->Batch_No = $res['batch_no'][$key];
                            $sample_data->Sl_No = $sl_no ?? null;
                            $sample_data->save();
                       
                    }
            }
        }
       else{
        if (isset($res['sl_no'])) {
            foreach ($res['sl_no'] as $key => $val) {
            foreach ($val as  $sl_no) { 
                        $sample_data = new SampleFreeGood_data;
                        $sample_data->SampleFreeGood_id = $sample->id;
                        $sample_data->Batch_No = $key;
                        $sample_data->Sl_No = $sl_no ?? null;
                        $sample_data->save();
                    }
                }
        }
       }

        return redirect('SampleFreeGood/SampleFreeGoodList')->with('success', 'Added Successfully....');
    }
}
