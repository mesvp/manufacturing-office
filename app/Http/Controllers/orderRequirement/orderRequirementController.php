<?php

namespace App\Http\Controllers\orderRequirement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\orderRequirement\{Order_Requirement_Sales, Order_Requirement_Stock, Order_Requirement_Product_Details, Order_Requirement_Stock_Approve, Order_Requirement_Sales_Approve};
use Session;

class orderRequirementController extends Controller
{
    public function AddSales(Request $request)
    {
       
        if ($request->edit) {
            $Sales = Order_Requirement_Sales::find($request->edit);
        } else {
            $Sales = new Order_Requirement_Sales;
            $Sales->userID = auth()->user()->id;
            $Sales->status = 0;
            $Sales->Forward_Status = 0;
        }
        $Sales->Work_Order_Type = 'Sales';
        $Sales->Organization = $request->Organization;
        $Sales->BU_Name = $request->BU_Name;
        $Sales->Customer_Name = $request->Customer_Name;
        $Sales->Unit_Name = $request->Unit_Name;
        $Sales->Plant_Name = $request->Plant_Name;
        $Sales->Order_Date = $request->Order_Date;
        $Sales->Company_Name = $request->Company_Name;
        $Sales->country = $request->country;
        $Sales->state = $request->state;
        $Sales->district = $request->district;
        $Sales->Zip_Code = $request->Zip_Code;
        $Sales->Phone = $request->Phone;
        $Sales->Address = $request->Address;
        $Sales->Fax = $request->Fax;
        $Sales->GST = $request->GST;
        $Sales->Fax = $request->Fax;
        $Sales->Dispatch_Date = $request->Dispatch_Date;
        $Sales->Brand_Label = $request->Brand_Label;
        $Sales->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $Sales->status = 0;
        } else {
            $Sales->status = 1;
        }

        $Sales->save();

        $Sales->Sales_Order_No = 'SALES' . str_pad($Sales->id, 4, '0', STR_PAD_LEFT);

        $Sales->save();

        if (!isset($request->draft)) {
            $Approve_step = Order_Requirement_Sales::where('id', $Sales->id)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = Order_Requirement_Sales::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = Order_Requirement_Sales::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = Order_Requirement_Sales_Approve::where('Order_Requirement_Sales_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new Order_Requirement_Sales_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->Order_Requirement_Sales_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('orderRequirement/orderRequirementList')->with('success', 'Added Successfully....');
    }

    public function AddStock(Request $request)
    {
         //return $request->all();
        //return $request->edit;
        if ($request->edit) {
             $Stock = Order_Requirement_Stock::find($request->edit);
        } else {
            $Stock = new Order_Requirement_Stock;
            $Stock->userID = auth()->user()->id;
            $Stock->status = 0;
            $Stock->Forward_Status = 0;
        }
        $Stock->Work_Order_Type = 'Stock';
        $Stock->Organization = $request->Organization;
        $Stock->BU_Name = $request->BU_Name;
        $Stock->Factory_Godown_Name = $request->Factory_Godown_Name;
        $Stock->Unit_Name = $request->Unit_Name;
        $Stock->Plant_Name = $request->Plant_Name;
        $Stock->Expected_Date = $request->Expected_Date;
        $Stock->Company_Name = $request->Company_Name;
        $Stock->Raw_Material = $request->Raw_Material;
        $Stock->HSN_Code = $request->HSN_Code;
        $Stock->UOM = $request->UOM;
        $Stock->QTY = $request->QTY;
        $Stock->Total = $request->Total;
        $Stock->remarks = $request->remarks;
        $Stock->billing_address = $request->billing_address;
        $Stock->billing_details = $request->billing_details;
        $Stock->shipping_address = $request->shipping_address;
        $Stock->shiping_details = $request->shiping_details;
        $Stock->delivery_address = $request->delivery_address;
        $Stock->phone = $request->phone;
        $Stock->contact_psrn = $request->contact_psrn;
        $Stock->procurement_type = $request->procurement_type;
        if (!isset($request->draft)) {
            $Stock->status = 0;
        } else {
            $Stock->status = 1;
        }

        $Stock->save();

        $Stock->Stock_Order_No = 'M' . str_pad($Stock->id, 4, '0', STR_PAD_LEFT);

        $Stock->save();
        if($request->procurement_type == "Normal"){
            if ($request->edit != ''){
                if ($request->Normal !== null) {
                $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                }
                if ($request->Normal == "") {
                }else{
                    $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                    $res = $request->input();
    
                    if (isset($res['Material_Name']) && $res['Material_Name'] != '') {
                        foreach ($res['Material_Name'] as $key => $val) {
        
                            $Stage = new Order_Requirement_Product_Details;
                            $Stage->Stock_id = $Stock->id;
                            $Stage->Material_Name = $res['Material_id'][$key] ?? '';
                            $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                            $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                            $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                            $Stage->Rate = $res['Rate'][$key] ?? '';
                            $Stage->Amount = $res['Amount'][$key] ?? '';
                            $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                            $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';
        
                            $Stage->save();
                        }
                    }

                }
    
            }else{
                $res = $request->input();
    
                if (isset($res['Material_Name']) && $res['Material_Name'] != '') {
                    foreach ($res['Material_Name'] as $key => $val) {
    
                        $Stage = new Order_Requirement_Product_Details;
                        $Stage->Stock_id = $Stock->id;
                        $Stage->Material_Name = $res['Material_id'][$key] ?? '';
                        $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                        $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                        $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                        $Stage->Rate = $res['Rate'][$key] ?? '';
                        $Stage->Amount = $res['Amount'][$key] ?? '';
                        $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                        $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';
    
                        $Stage->save();
                    }
                }
            }
        }
        if($request->procurement_type == "Loose"){
                if ($request->edit != ''){
                    if ($request->loose !== null) {
                    $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                    }
                    if ($request->loose == "") {
                    }else{
                    //return 1;
                    $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                    $res = $request->input();
                    $checked_array=$res['check'];
                    //print_r($checked_array);
                        if(sizeof($checked_array) >0){
                            foreach($checked_array as $key=>$value){

                                $Stage = new Order_Requirement_Product_Details;
                                $Stage->Stock_id = $Stock->id;
                                $Stage->Material_Name = $res['Material_id'][$key] ?? '';
                                $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                                $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                                $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                                $Stage->Rate = $res['Rate'][$key] ?? '';
                                $Stage->Amount = $res['Amount'][$key] ?? '';
                                $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                                $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';

                                $Stage->save();
                            }
                        }
                    }
                }else{
                        $res = $request->input();
                        $checked_array=$res['check'];
                        //print_r($checked_array);
                        if(sizeof($checked_array) >0){
                            foreach($checked_array as $key=>$value){

                                $Stage = new Order_Requirement_Product_Details;
                                $Stage->Stock_id = $Stock->id;
                                $Stage->Material_Name = $res['Material_id'][$key] ?? '';
                                $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                                $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                                $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                                $Stage->Rate = $res['Rate'][$key] ?? '';
                                $Stage->Amount = $res['Amount'][$key] ?? '';
                                $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                                $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';

                                $Stage->save();
                            }
                    }

                }
        }
        if($request->procurement_type == "Additional"){
            if ($request->edit != ''){
                if ($request->Additional !== null) {
                $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                }
                if ($request->Additional == "") {
                }else{
                    //return 1;
                    $DeletePrivious = Order_Requirement_Product_Details::where('Stock_id', $Stock->id)->delete();
                    $res = $request->input();
                    if (isset($res['Material_Name']) && $res['Material_Name'] != '') {
                        //return 2;
                        foreach ($res['Material_Name'] as $key => $val) {
        
                            $Stage = new Order_Requirement_Product_Details;
                            $Stage->Stock_id = $Stock->id;
                            $Stage->Material_Name = $res['Material_Name'][$key] ?? '';
                            $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                            $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                            $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                            $Stage->Rate = $res['Rate'][$key] ?? '';
                            $Stage->Amount = $res['Amount'][$key] ?? '';
                            $Stage->GST_Per = $res['GST_Per'][$key] ?? '';
                            $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                            $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';
        
                            $Stage->save();
                        }
                    }
                }
            }else{
                $res = $request->input();
    
                if (isset($res['Material_Name']) && $res['Material_Name'] != '') {
                    foreach ($res['Material_Name'] as $key => $val) {
    
                        $Stage = new Order_Requirement_Product_Details;
                        $Stage->Stock_id = $Stock->id;
                        $Stage->Material_Name = $res['Material_Name'][$key] ?? '';
                        $Stage->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                        $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';
                        $Stage->Total_QTY = $res['Total_QTY'][$key] ?? '';
                        $Stage->Rate = $res['Rate'][$key] ?? '';
                        $Stage->Amount = $res['Amount'][$key] ?? '';
                        $Stage->GST_Per = $res['GST_Per'][$key] ?? '';
                        $Stage->GST_Value = $res['GST_Value'][$key] ?? '';
                        $Stage->Sub_Total = $res['Sub_Total'][$key] ?? '';
    
                        $Stage->save();
                    }
                }

            }
        }

        if (!isset($request->draft)) {            
            $Approve_step = Order_Requirement_Stock::where('id', $Stock->id)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = Order_Requirement_Stock::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = Order_Requirement_Stock::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = Order_Requirement_Stock_Approve::where('Order_Requirement_Stock_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new Order_Requirement_Stock_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->Order_Requirement_Stock_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('orderRequirement/orderRequirementList')->with('success', 'Added Successfully....');
    }
}
