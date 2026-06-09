<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_Raw_Material,Master_Raw_Material_Detail, Master_OB, Master_Received_QTY, Master_Rack_No, Master_Sub_Rack_No, Master_Bin_No, Master_Sub_Bin_No, Master_Gate_Pass_Required, Master_HSN_Code, Master_Material_Name};
use App\Models\FactoryCreater\{Factory_Organisation,prj_organisation};
use App\Models\Master\BOM\{Master_GST_Percentage};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use Session;


class RawMaterialController extends Controller
{
    public function Godown_Name($id = null)
    {
        $Godown_Name = Master_Godown_Name::all();
        $edit = Master_Godown_Name::find($id);

        return view('Master.RawMaterial.Godown_Name', ['Godown_Name' => $Godown_Name, 'edit' => $edit]);
    }

    public function Godown_Name_store(Request $request)
    {
        $duplicate = Master_Godown_Name::where('Godown_Name', $request->Godown_Name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Godown_Name::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Godown_Name;
            }
            $plant->Godown_Name = $request->Godown_Name;
            $plant->save();
        } else {
            return redirect('Master/Godown_Name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Godown_Name')->with('success', 'Added Successfully...');
    }

    public function delete_Godown_Name($id)
    {
        Master_Godown_Name::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Raw_Material($id = null)
    {
        
        $Organization = prj_organisation::all();
        $Godown_Name = Prj_Inventory::select('prj_inventory.*')->where('godown_type','69')->get();
        $GST = Master_GST_Percentage::all();
        // $product_data = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        // $Raw_Material = [];
        // foreach ($product_data as $Val) {
        //     if (isset($Val->Raw_Material)) {
        //         $Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
        //         $Raw_Material[] = $Val;
        //     }
        // }
       
        $Raw_Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                        ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('Approve_status', 'APPROVE')->get();
        

        $Godown_Material = Master_Raw_Material::select('master_raw_material.*','mstr_emp.fullname')
                            ->leftJoin('mstr_emp','master_raw_material.created_by','=','mstr_emp.id')
                            ->orderBy('master_raw_material.id', 'desc')
                            ->get();
        $GodownData = [];
        foreach ($Godown_Material as $val) {
            $val->organization = prj_organisation::find($val->Organization);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            //$val->Material = MaterialManagement_Add_Material::find($val->Material);
            $val->Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->Material)
                    ->first();

           

            $GodownData[] = $val;
        }
        // echo "<pre>";
        // print_r( $Raw_Material);
        // echo "</pre>";

        $edit = Master_Raw_Material::find($id);

        return view('Master.RawMaterial.Raw_Material', ['Godown_Material' => $GodownData, 'Material' => $Raw_Material, 'edit' => $edit, 'Godown_Name' => $Godown_Name, 'Organization' => $Organization, 'GST' => $GST]);
    }
    public function View_details_material($id){

        $Godown_Material_details = Master_Raw_Material_Detail::select('master_raw_material_details.*','mstr_emp.fullname')
                            ->leftJoin('mstr_emp','master_raw_material_details.created_by','=','mstr_emp.id')
                            ->where('master_raw_material_details.raw_mat_id',$id)
                            ->get();
        $GodownData_details = [];
        foreach ($Godown_Material_details as $val) {
            $val->organization = prj_organisation::find($val->Organization);
            $val->Godown_Name = Prj_Inventory::find($val->Godown_Name);
            //$val->Material = MaterialManagement_Add_Material::find($val->Material);
            $val->Material = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name as matname')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->where('materialmanagement_add_material.id',$val->Material)
                    ->first();
            $GodownData_details[] = $val;
        }
        return view('Master.RawMaterial.Raw_Material_details', ['Godown_Material_details' => $Godown_Material_details,'Godown_Material' => $GodownData_details]);
    }

    public function Raw_Material_store(Request $request)
    {
         $duplicate = Master_Raw_Material::where(['Organization'=>$request->Organization,'Godown_Name'=>$request->Godown_Name,'Material'=>$request->Material]);;
        if ($duplicate->count() > 0) {
           
                $plant = $duplicate->first();
                $plant->Quantity = $request->Quantity+$plant->Quantity;
                $plant->Amount = $request->Amount+$plant->Amount;
         } else {
                $plant = new Master_Raw_Material;
                $plant->Quantity = $request->Quantity;
                $plant->Amount = $request->Amount;
            }
           // $plant->Raw_Material = $request->Raw_Material;
            $plant->Organization = $request->Organization;
            $plant->Godown_Name = $request->Godown_Name;
            $plant->Material = $request->Material;
            $plant->Date = $request->Date;
            $plant->created_by = auth()->user()->id;
            
            $plant->Rate = $request->Rate;
            $plant->GST = $request->GST;
            $plant->reason = $request->reason;
            
            $plant->save();
            
            $plantdetails=new Master_Raw_Material_Detail;
            $plantdetails->raw_mat_id = $plant->id;
            $plantdetails->Quantity = $request->Quantity;
            $plantdetails->Amount = $request->Amount;
            $plantdetails->Organization = $request->Organization;
            $plantdetails->Godown_Name = $request->Godown_Name;
            $plantdetails->Material = $request->Material;
            $plantdetails->Date = $request->Date;
            $plantdetails->created_by = auth()->user()->id;
            $plantdetails->Rate = $request->Rate;
            $plantdetails->GST = $request->GST;
            $plantdetails->reason = $request->reason;
            $plantdetails->save();

        // } else {
        //     return redirect('Master/Raw_Material')->with('error', 'can not save duplicate data....');
        // }
        return redirect('Master/Raw_Material')->with('success', 'Added Successfully...');
    }

    public function delete_Raw_Material($id)
    {
        Master_Raw_Material::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function OB($id = null)
    {
        $OB = Master_OB::all();
        $edit = Master_OB::find($id);

        return view('Master.RawMaterial.OB', ['OB' => $OB, 'edit' => $edit]);
    }

    public function OB_store(Request $request)
    {
        $duplicate = Master_OB::where('OB', $request->OB)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_OB::where('id', $request->edit)->first();
            } else {
                $plant = new Master_OB;
            }
            $plant->OB = $request->OB;
            $plant->save();
        } else {
            return redirect('Master/OB')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/OB')->with('success', 'Added Successfully...');
    }

    public function delete_OB($id)
    {
        Master_OB::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Received_QTY($id = null)
    {
        $Received_QTY = Master_Received_QTY::all();
        $edit = Master_Received_QTY::find($id);

        return view('Master.RawMaterial.Received_QTY', ['Received_QTY' => $Received_QTY, 'edit' => $edit]);
    }

    public function Received_QTY_store(Request $request)
    {
        $duplicate = Master_Received_QTY::where('Received_QTY', $request->Received_QTY)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Received_QTY::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Received_QTY;
            }
            $plant->Received_QTY = $request->Received_QTY;
            $plant->save();
        } else {
            return redirect('Master/Received_QTY')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Received_QTY')->with('success', 'Added Successfully...');
    }

    public function delete_Received_QTY($id)
    {
        Master_Received_QTY::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Rack_No($id = null)
    {
        $Rack_No = Master_Rack_No::all();
        $edit = Master_Rack_No::find($id);

        return view('Master.RawMaterial.Rack_No', ['Rack_No' => $Rack_No, 'edit' => $edit]);
    }

    public function Rack_No_store(Request $request)
    {
        $duplicate = Master_Rack_No::where('Rack_No', $request->Rack_No)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Rack_No::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Rack_No;
            }
            $plant->Rack_No = $request->Rack_No;
            $plant->save();
        } else {
            return redirect('Master/Rack_No')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Rack_No')->with('success', 'Added Successfully...');
    }

    public function delete_Rack_No($id)
    {
        Master_Rack_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Sub_Rack_No($id = null)
    {
        $Sub_Rack_No = Master_Sub_Rack_No::all();
        $edit = Master_Sub_Rack_No::find($id);

        return view('Master.RawMaterial.Sub_Rack_No', ['Sub_Rack_No' => $Sub_Rack_No, 'edit' => $edit]);
    }

    public function Sub_Rack_No_store(Request $request)
    {
        $duplicate = Master_Sub_Rack_No::where('Sub_Rack_No', $request->Sub_Rack_No)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Sub_Rack_No::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Sub_Rack_No;
            }
            $plant->Sub_Rack_No = $request->Sub_Rack_No;
            $plant->save();
        } else {
            return redirect('Master/Sub_Rack_No')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Sub_Rack_No')->with('success', 'Added Successfully...');
    }

    public function delete_Sub_Rack_No($id)
    {
        Master_Sub_Rack_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Bin_No($id = null)
    {
        $Bin_No = Master_Bin_No::all();
        $edit = Master_Bin_No::find($id);

        return view('Master.RawMaterial.Bin_No', ['Bin_No' => $Bin_No, 'edit' => $edit]);
    }

    public function Bin_No_store(Request $request)
    {
        $duplicate = Master_Bin_No::where('Bin_No', $request->Bin_No)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Bin_No::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Bin_No;
            }
            $plant->Bin_No = $request->Bin_No;
            $plant->save();
        } else {
            return redirect('Master/Bin_No')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Bin_No')->with('success', 'Added Successfully...');
    }

    public function delete_Bin_No($id)
    {
        Master_Bin_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Sub_Bin_No($id = null)
    {
        $Sub_Bin_No = Master_Sub_Bin_No::all();
        $edit = Master_Sub_Bin_No::find($id);

        return view('Master.RawMaterial.Sub_Bin_No', ['Sub_Bin_No' => $Sub_Bin_No, 'edit' => $edit]);
    }

    public function Sub_Bin_No_store(Request $request)
    {
        $duplicate = Master_Sub_Bin_No::where('Sub_Bin_No', $request->Sub_Bin_No)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Sub_Bin_No::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Sub_Bin_No;
            }
            $plant->Sub_Bin_No = $request->Sub_Bin_No;
            $plant->save();
        } else {
            return redirect('Master/Sub_Bin_No')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Sub_Bin_No')->with('success', 'Added Successfully...');
    }

    public function delete_Sub_Bin_No($id)
    {
        Master_Sub_Bin_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Material_Name($id = null)
    {
        $Material_Name = Master_Material_Name::all();
        $edit = Master_Material_Name::find($id);

        return view('Master.RawMaterial.Material_Name', ['Material_Name' => $Material_Name, 'edit' => $edit]);
    }

    public function Material_Name_store(Request $request)
    {
        $duplicate = Master_Material_Name::where('Material_Name', $request->Material_Name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Material_Name::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Material_Name;
            }
            $plant->Material_Name = $request->Material_Name;
            $plant->save();
        } else {
            return redirect('Master/Material_Name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Material_Name')->with('success', 'Added Successfully...');
    }

    public function delete_Material_Name($id)
    {
        Master_Material_Name::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function HSN_Code($id = null)
    {
        $HSN_Code = Master_HSN_Code::all();
        $edit = Master_HSN_Code::find($id);

        return view('Master.RawMaterial.HSN_Code', ['HSN_Code' => $HSN_Code, 'edit' => $edit]);
    }

    public function HSN_Code_store(Request $request)
    {
        $duplicate = Master_HSN_Code::where('HSN_Code', $request->HSN_Code)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_HSN_Code::where('id', $request->edit)->first();
            } else {
                $plant = new Master_HSN_Code;
            }
            $plant->HSN_Code = $request->HSN_Code;
            $plant->save();
        } else {
            return redirect('Master/HSN_Code')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/HSN_Code')->with('success', 'Added Successfully...');
    }

    public function delete_HSN_Code($id)
    {
        Master_HSN_Code::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Gate_Pass_Required($id = null)
    {
        $Gate_Pass_Required = Master_Gate_Pass_Required::all();
        $edit = Master_Gate_Pass_Required::find($id);

        return view('Master.RawMaterial.Gate_Pass_Required', ['Gate_Pass_Required' => $Gate_Pass_Required, 'edit' => $edit]);
    }

    public function Gate_Pass_Required_store(Request $request)
    {
        $duplicate = Master_Gate_Pass_Required::where('Gate_Pass_Required', $request->Gate_Pass_Required)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Gate_Pass_Required::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Gate_Pass_Required;
            }
            $plant->Gate_Pass_Required = $request->Gate_Pass_Required;
            $plant->save();
        } else {
            return redirect('Master/Gate_Pass_Required')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Gate_Pass_Required')->with('success', 'Added Successfully...');
    }

    public function delete_Gate_Pass_Required($id)
    {
        Master_Gate_Pass_Required::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
