<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialManagement\{MaterialManagement_Add_Material, Material_Management_approve};
use Session;


class MaterialManagementController extends Controller
{
    public function AddMaterial(Request $request)
    {
        //return $request->all();
        if ($request->edit != '') {
                if (!isset($request->draft)) {
                    $request->validate([
                        'Material_id' => 'required',
                        
                    ]);
                }
        }else{
            if (!isset($request->draft)) {
                $request->validate([
                    'Material_Name' => 'required',
                    'Material_id' => 'required',
                    
                ]);
            }
        }

        if ($request->edit != '') {
            $addmaterial = MaterialManagement_Add_Material::find($request->edit);
            $addmaterial->remarks = $request->remarks;
        } else {
            $addmaterial = new MaterialManagement_Add_Material;
            $addmaterial->userID = auth()->user()->id;
            $addmaterial->Forward_Status = 0;
            $addmaterial->Used_Status = 0;
            $addmaterial->Used_Status_RM = 0;
            $addmaterial->Material_Name = $request->Material_Name;
            $addmaterial->Material_id = $request->Material_id;
            $addmaterial->HSN_Code = $request->HSN_Code;
            $addmaterial->UOM = $request->UOM;
            $addmaterial->last_purchase_price = $request->last_purchase_price;
            $addmaterial->last_purchase_date = $request->last_purchase_date;
            $addmaterial->last_purchase_vndr_name = $request->last_purchase_vndr_name;
            $addmaterial->grp_name = $request->grp_name;
            $addmaterial->sub_grp_name = $request->sub_grp_name;
            $addmaterial->cat_name = $request->cat_name;
            $addmaterial->sub_cat_name = $request->sub_cat_name;
            $addmaterial->remarks = $request->remarks;
        }

        
        if (!isset($request->draft)) {
            $addmaterial->status = 0;
        } else {
            $addmaterial->status = 1;
        }
        if ($request->edit != '') {
            $addmaterial->save();
        }else{
            $material_apprvsts=MaterialManagement_Add_Material::select('materialmanagement_add_material.Approve_status')->where('Material_Name',$request->Material_Name)->orderBy('id', 'DESC')->first();
            if ($material_apprvsts !== null) {
                $apprvstatus = $material_apprvsts->Approve_status;
                if($material_apprvsts->Approve_status=="" || $material_apprvsts->Approve_status=="APPROVE" || $material_apprvsts->Approve_status=="RECHECK" || $material_apprvsts->Approve_status=="HOLD" || $material_apprvsts->Approve_status=="FORWARD" ||$material_apprvsts->Approve_status=="OBJECT"){
                    return redirect('MaterialManagement/MaterialList')->withErrors( 'Already Added...');
                }
                else {
                    $addmaterial->save();
                }
            } 
            else {
                $addmaterial->save();
                $addmaterial->Material_Code = 'MC' . str_pad($addmaterial->id, 4, '0', STR_PAD_LEFT);
                $addmaterial->save();
            }

        }
       
                 
                if (!isset($request->draft)) {
                    $Approve_step = MaterialManagement_Add_Material::where('id', $addmaterial->id)->update(['Approve_Step' => 1]);
                }
        
                if ($request->edit != '' && !isset($request->draft)) {
                    $dataStatus = MaterialManagement_Add_Material::find($request->edit);
                    if ($dataStatus->Approve_status != '') {
                        $rechecked = MaterialManagement_Add_Material::where('id', $request->edit)->update(['Approve_status' => null]);
                        $status = Material_Management_approve::where('Material_Management_id', $request->edit)->where('status', 1)->update(['status' => 0]);
        
                        $approve = new Material_Management_approve;
                        $approve->userID = auth()->user()->id;
                        if (auth()->user()->role == 0) {
                            $approve->role = 'Admin';
                        } else {
                            $approve->role = 'Inputer';
                        }
                        $approve->Material_Management_id = $request->edit;
                        $approve->status = 1;
                        $approve->action = 'Checked';
                        $approve->ip_address = $request->getClientIp();
                        $approve->device_name = $request->header('User-Agent');
        
                        $approve->save();
                    }
                }
        
                return redirect('MaterialManagement/MaterialList')->with('success', 'Added Successfully....');
    
            
        
        
    }

    public function delete($id)
    {
        $delete = MaterialManagement_Add_Material::find($id)->delete();

        return redirect('MaterialManagement/MaterialList')->with('success', 'Deleted Successfully....');
    }
}
