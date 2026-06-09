<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryManagement\{Inventory_Management_Approve,Inventory_Management, Inventory_Management_Data, Inventory_Management_Product, Inventory_Management_Material, Inventory_Management_Godown};

class InventoryManagementController extends Controller
{
    public function recheck($request)
    {
        $approve = new Inventory_Management_Approve;
        $approve->userID = auth()->user()->id;
        $approve->role = 'Inputer';
        $approve->Inventory_Management_id = $request->edit;
        $approve->status = 1;
        $approve->action = 'Checked';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();
    }
    public function AddInventoryManagement(Request $request)
    {
        $request->validate([
            'Unit_Name' => 'required',
            'Plant_Name' => 'required',
            'Organization' => 'required',
            'BU' => 'required',
            'batch_no' => 'required',
            'Rack_No.*' => 'required',
            'Sub_Rack_No.*' => 'required',
            'Bin_No.*' => 'required',
            'Sub_Bin_No.*' => 'required',
            'manage.*' => 'required',

        ]);
        if ($request->edit != '') {
            $Inventory = Inventory_Management::find($request->edit);
            $Inventory->Approve_status=null;
            $this->recheck($request);
        } else {
            $Inventory = new Inventory_Management;
            $Inventory->userID = auth()->user()->id;
            $Inventory->Approve_Step = 1;
        }
        $Inventory->Unit_Name=$request->Unit_Name;
        $Inventory->Plant_Name=$request->Plant_Name;
        $Inventory->Organization_Name=$request->Organization;
        $Inventory->BU_Name=$request->BU;
        $Inventory->Raw_Material=$request->fid;
        $Inventory->batch_no=$request->batch_no;
        $Inventory->QCCode=$request->QCCode;
        $Inventory->remarks=$request->remark;
        $Inventory->save();
        $inputs=$request->input();
        if($request->edit)
        {
            Inventory_Management_Material::where('Inventory_Management_Product_Id',$request->edit)->delete();
        }
        foreach($inputs['Rack_No'] as $key=> $Rack_No)
        {
            $inv_mat=new Inventory_Management_Material;
            $inv_mat->Inventory_Management_Product_Id=$Inventory->id;
            $inv_mat->Rack_No=$inputs['Rack_No'][$key];
            $inv_mat->Sub_Rack_No=$inputs['Rack_No'][$key];
            $inv_mat->Bin_No=$inputs['Bin_No'][$key];
            $inv_mat->Sub_Bin_No=$inputs['Sub_Bin_No'][$key];
            $inv_mat->save();
            foreach($inputs['manage'][$key] as $i => $sl_no)
            {
                $inv_rack=new Inventory_Management_Product;
                $inv_rack->Inventory_Management_Material_id=$inv_mat->id;
                $inv_rack->material_sl_no=$sl_no;
                $inv_rack->save();
            }
        }

       

        return redirect('InventoryManagement/InventoryManagementList')->with('success', 'Added Successfully....');
    }
}
