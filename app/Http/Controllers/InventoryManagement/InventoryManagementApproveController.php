<?php

namespace App\Http\Controllers\InventoryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryManagement\{Inventory_Management, Inventory_Management_Data, Inventory_Management_Product, Inventory_Management_Material, Inventory_Management_Godown, Inventory_Management_Approve};
use App\Models\Master\Plant\{Master_Manufacturing_unit,Master_BU, Master_Quality_Check};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\{CheckBox, Admin, Forwarded_Data,Department_Assign};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Uom, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product,Factory_Address_Detail,prj_organisation};
use App\Models\Master\RawMaterial\{Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No, Master_Raw_Material};
use App\Models\Master\Gatepass\Master_employee_names;
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\BOM\{BOM, BOM_Material};
use Session;


class InventoryManagementApproveController extends Controller
{
    public function InventoryManagementList(Request $request)
    {
        $orgdata=prj_organisation::all_org();
        $BUdata = Module_Bsns_Unit::all_bu();
        $Manufacturing_unitdata = prj_project::all_mu();
        $plant_namedata = Prj_Subproject::all_pm();
        $admindata=Admin::all_admin();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Raw_Material = [];
        $Raw_Materialdata=[];
        foreach ($BOM_DATA as $Val) {
            if (isset($Val->Raw_Material_FG)) {
                //$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material_FG);
                $Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$Val->Raw_Material_FG)
                    ->first();
                $Raw_Material[$Val->Raw_Material_FG] = $Val;
                $Raw_Materialdata[$Val->Raw_Material_FG]= $Val->RawMaterial->matname;
            }
        }
        /////////////////////////////////////
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $EXT = Session::get('EXT');
        $query = Inventory_Management::where('status',0)->orderBy('id', 'DESC');
        if (isset($EXT[14]['Forward']) && isset($EXT[14]['approver']))
        {
           $query = $query->where(function ($query) use ($EXT) {
               $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[14]['approver']) . ")");
           })
               ->orWhere(function ($query) {
                   $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
               })
               ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
               ->orderBy('id', 'DESC');
       } 
       elseif (isset($EXT[14]['Forward']))
        {       
           $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
       } 
       elseif (isset($EXT[14]['approver'])) 
       {
           
           $query = $query->where('Approve_status', null)->where(['Forward_Status' => 0, 'status' => 0])->WhereRaw("Approve_Step IN (" . implode(",", $EXT[14]['approver']) . ")")->orderBy('id', 'DESC');
       }
        if ($fromdate && $todate) 
        {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }
      
        $InventoryManagement=$query->get();

        
        return view('InventoryManagement/InventoryManagementApproverList', compact('InventoryManagement','orgdata','BUdata','Manufacturing_unitdata','plant_namedata','admindata','Raw_Materialdata'));
    }
    public function view($id)
    {
        $type=1;
        $edit = Inventory_Management::find($id);
        $admindata=Admin::all_admin()[$edit->userID];
        return view('InventoryManagement/mainview',compact('id','type','admindata'));
    }
    


    public function approve(Request $request)
    {
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Inventory_Management::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Inventory_Management_Approve::where('Inventory_Management_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
          //  echo "ravi";
        }
        //die;
        $check = Inventory_Management::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Inventory_Management::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 14, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 14, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Inventory_Management::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }

            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Inventory_Management::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }

        if ($request->during_approval === 'REJECT') {
           // MaterialManagement_Add_Material::where('id', $check->Raw_Material)->update(['Used_Status' => 0]);
        }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 14, 'DataID' => $request->approveID])->update(['status' => 1]);
            Inventory_Management::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 14;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Inventory_Management_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[14]['Inputer'])) {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[14]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->Inventory_Management_id = $request->approveID;
        $approve->status = 1;
        if ($request->during_approval != '') {
            $approve->action = $request->during_approval;
        } elseif ($request->pre_post_approval != '') {
            $approve->pre_post_approval = $request->pre_post_approval;
        } else {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;

        $approve->save();

        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            Inventory_Management::where('id', $request->approveID)->update(['Approve_status' => null]);
            return redirect('InventoryManagement/InventoryManagementList')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('InventoryManagement/InventoryManagementList')->with('success', 'successfull.....');
        } else {
            return redirect('InventoryManagement/InventoryManagementApproverList')->with('success', 'Approved successfully.....');
        }
    }
}
