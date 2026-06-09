<?php

namespace App\Http\Controllers\BOM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BOM\{BOM, BOM_Consumbles, BOM_Expenses, BOM_Machine, BOM_Management, BOM_Manpower, BOM_Material, BOM_Services, BOM_Transport, BOM_Approve};
use Session;

class BOMController extends Controller
{
    public function AddBOM(Request $request)
    {
        //return $request->all();
        if (!isset($request->draft)) {
            $required = [
                // 'Organization' => 'required',
                // 'Manufacturing_Unit' => 'required',
                // 'Plant_Name' => 'required',
                // 'Category' => 'required',
                // 'Product' => 'required',
                // 'Sub_Product' => 'required',
                // 'Sub_Sub_Product' => 'required',
                //'Color' => 'required',
                'All_Total_Amount' => 'required',
                'Material.*' => 'required',
                'HSN_Code_Second.*' => 'required',
                //'UOM.*' => 'required',
                'Material_QTY.*' => 'required',
                'Scarp_QTY.*' => 'required',
                'Total_QTY.*' => 'required',
                'Basic_Amount_unit.*' => 'required',
                'Total_Basic_Amount.*' => 'required',
                'GST_Percentage.*' => 'required',
                'GST_Value.*' => 'required',
                'Total_Amount.*' => 'required',
                'Manpower_Skill.*' => 'required',
                'Manpower_Count.*' => 'required',
                'Average_Salary.*' => 'required',
                'Machine_Specification.*' => 'required',
                'Production_Capacity_Per_Shift.*' => 'required',
                'UOM_Second.*' => 'required',
                'Services.*' => 'required',
                'Services_Amount.*' => 'required',
                'Consumbles.*' => 'required',
                'Consumbles_Amount.*' => 'required',
                'Management_Expenses.*' => 'required',
                'Management_Expenses_Amount.*' => 'required',
                'Other_Expenses.*' => 'required',
                'Other_Expenses_Amount.*' => 'required',
            ];
            $request->validate($required);
        }

        if ($request->edit != '') {
            $BOM = BOM::find($request->edit);
        } else {
            $BOM = new BOM;
            $BOM->userID = auth()->user()->id;
            $BOM->Forward_Status = 0;
        }
        $BOM->Organization = $request->Organization;
        $BOM->Manufacturing_Unit = $request->Manufacturing_Unit;
        $BOM->Plant_Name = $request->Plant_Name;
        $BOM->Category = $request->Category;
        $BOM->Product = $request->Product;
        $BOM->Sub_Product = $request->Sub_Product;
        $BOM->Sub_Sub_Product = $request->Sub_Sub_Product;
        $BOM->Color = $request->Color;
        $BOM->Raw_Material_FG = $request->Raw_Material_FG;
        $BOM->HSN_Code_FG = $request->HSN_Code_FG;
        $BOM->UOMFG = $request->UOMFG;
        $BOM->All_Total_Amount = $request->All_Total_Amount;
        $BOM->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $BOM->status = 0;
        } else {
            $BOM->status = 1;
        }

        $BOM->save();

        $BOM->BOM_Name = 'BOMN' . str_pad($BOM->id, 5, '0', STR_PAD_LEFT);
        $BOM->BOM_Code = 'BOMC' . str_pad($BOM->id, 5, '0', STR_PAD_LEFT);

        $BOM->save();


        if ($request->edit != '') {
            $BOMID = $request->edit;
        } else {
            $BOMID = $BOM->id;
        }

        $res = $request->input();

        if (isset($res['Material']) && $res['Material'] != '') {
            foreach ($res['Material'] as $key => $val) {
                $MaterialID = isset($res['MaterialID'][$key]) ? $res['MaterialID'][$key] : '';
                if ($MaterialID != '') {
                    $Material = BOM_Material::where('id', $MaterialID)->update(['Material' => $res['Material'][$key] ?? 0, 'HSN_Code_Second' => $res['HSN_Code_Second'][$key] ?? '', 'UOM' => $res['UOM'][$key] ?? 0, 'Material_QTY' => $res['Material_QTY'][$key] ?? '', 'Scarp_QTY' => $res['Scarp_QTY'][$key] ?? '', 'Total_QTY' => $res['Total_QTY'][$key] ?? '', 'Basic_Amount_unit' => $res['Basic_Amount_unit'][$key] ?? '', 'Total_Basic_Amount' => $res['Total_Basic_Amount'][$key] ?? '', 'GST_Percentage' => $res['GST_Percentage'][$key] ?? 0, 'GST_Value' => $res['GST_Value'][$key] ?? '', 'Total_Amount' => $res['Total_Amount'][$key] ?? '']);
                } else {
                    $Material = new BOM_Material;
                    $Material->BOM_ID = $BOMID;
                    $Material->Material = $res['Material'][$key] ?? 0;
                    $Material->HSN_Code_Second = $res['HSN_Code_Second'][$key] ?? '';
                    $Material->UOM = $res['UOM'][$key] ?? 0;
                    $Material->Material_QTY = $res['Material_QTY'][$key] ?? '';
                    $Material->Scarp_QTY = $res['Scarp_QTY'][$key] ?? '';
                    $Material->Total_QTY = $res['Total_QTY'][$key] ?? '';
                    $Material->Basic_Amount_unit = $res['Basic_Amount_unit'][$key] ?? '';
                    $Material->Total_Basic_Amount = $res['Total_Basic_Amount'][$key] ?? '';
                    $Material->GST_Percentage = $res['GST_Percentage'][$key] ?? 0;
                    $Material->GST_Value = $res['GST_Value'][$key] ?? '';
                    $Material->Total_Amount = $res['Total_Amount'][$key] ?? '';

                    $Material->save();
                }
            }
        }

        if (isset($res['Manpower_Skill']) && $res['Manpower_Skill'] != '') {
            foreach ($res['Manpower_Skill'] as $key => $val) {
                $ManpowerID = isset($res['ManpowerID'][$key]) ? $res['ManpowerID'][$key] : '';
                if ($ManpowerID != '') {
                    $Manpower = BOM_Manpower::where('id', $ManpowerID)->update(['Manpower_Skill' => $res['Manpower_Skill'][$key] ?? '', 'Manpower_Count' => $res['Manpower_Count'][$key] ?? '', 'Average_Salary' => $res['Average_Salary'][$key] ?? '']);
                } else {
                    $Manpower = new BOM_Manpower;
                    $Manpower->BOM_ID = $BOMID;
                    $Manpower->Manpower_Skill = $res['Manpower_Skill'][$key] ?? '';
                    $Manpower->Manpower_Count = $res['Manpower_Count'][$key] ?? '';
                    $Manpower->Average_Salary = $res['Average_Salary'][$key] ?? '';

                    $Manpower->save();
                }
            }
        }

        if (isset($res['Machine_Specification']) && $res['Machine_Specification'] != '') {
            foreach ($res['Machine_Specification'] as $key => $val) {
                $MachineID = isset($res['MachineID'][$key]) ? $res['MachineID'][$key] : '';
                if ($MachineID != '') {
                    $Machine = BOM_Machine::where('id', $MachineID)->update(['Machine_Specification' => $res['Machine_Specification'][$key] ?? 0, 'Production_Capacity_Per_Shift' => $res['Production_Capacity_Per_Shift'][$key] ?? '', 'UOM_Second' => $res['UOM_Second'][$key] ?? 0]);
                } else {
                    $Machine = new BOM_Machine;
                    $Machine->BOM_ID = $BOMID;
                    $Machine->Machine_Specification = $res['Machine_Specification'][$key] ?? 0;
                    $Machine->Production_Capacity_Per_Shift = $res['Production_Capacity_Per_Shift'][$key] ?? '';
                    $Machine->UOM_Second = $res['UOM_Second'][$key] ?? 0;

                    $Machine->save();
                }
            }
        }

        if (isset($res['Services']) && $res['Services'] != '') {
            foreach ($res['Services'] as $key => $val) {
                $ServicesID = isset($res['ServicesID'][$key]) ? $res['ServicesID'][$key] : '';
                if ($ServicesID != '') {
                    $Services = BOM_Services::where('id', $ServicesID)->update(['Services' => $res['Services'][$key] ?? 0, 'Services_Amount' => $res['Services_Amount'][$key] ?? '']);
                } else {
                    $Services = new BOM_Services;
                    $Services->BOM_ID = $BOMID;
                    $Services->Services = $res['Services'][$key] ?? 0;
                    $Services->Services_Amount = $res['Services_Amount'][$key] ?? '';

                    $Services->save();
                }
            }
        }

        if (isset($res['Consumbles']) && $res['Consumbles'] != '') {
            foreach ($res['Consumbles'] as $key => $val) {
                $ConsumblesID = isset($res['ConsumblesID'][$key]) ? $res['ConsumblesID'][$key] : '';
                if ($ConsumblesID != '') {
                    $Consumbles = BOM_Consumbles::where('id', $ConsumblesID)->update(['Consumbles' => $res['Consumbles'][$key] ?? 0, 'Consumbles_Amount' => $res['Consumbles_Amount'][$key] ?? '']);
                } else {
                    $Consumbles = new BOM_Consumbles;
                    $Consumbles->BOM_ID = $BOMID;
                    $Consumbles->Consumbles = $res['Consumbles'][$key] ?? 0;
                    $Consumbles->Consumbles_Amount = $res['Consumbles_Amount'][$key] ?? '';

                    $Consumbles->save();
                }
            }
        }

        if (isset($res['Management_Expenses']) && $res['Management_Expenses'] != '') {
            foreach ($res['Management_Expenses'] as $key => $val) {
                $ManagementID = isset($res['ManagementID'][$key]) ? $res['ManagementID'][$key] : '';
                if ($ManagementID != '') {
                    $Management = BOM_Management::where('id', $ManagementID)->update(['Management_Expenses' => $res['Management_Expenses'][$key] ?? 0, 'Management_Expenses_Amount' => $res['Management_Expenses_Amount'][$key] ?? '']);
                } else {
                    $Management = new BOM_Management;
                    $Management->BOM_ID = $BOMID;
                    $Management->Management_Expenses = $res['Management_Expenses'][$key] ?? 0;
                    $Management->Management_Expenses_Amount = $res['Management_Expenses_Amount'][$key] ?? '';

                    $Management->save();
                }
            }
        }

        if (isset($res['Other_Expenses']) && $res['Other_Expenses'] != '') {
            foreach ($res['Other_Expenses'] as $key => $val) {
                $ExpensesID = isset($res['ExpensesID'][$key]) ? $res['ExpensesID'][$key] : '';
                if ($ExpensesID != '') {
                    $Expenses = BOM_Expenses::where('id', $ExpensesID)->update(['Other_Expenses' => $res['Other_Expenses'][$key] ?? '', 'Other_Expenses_Amount' => $res['Other_Expenses_Amount'][$key] ?? '']);
                } else {
                    $Expenses = new BOM_Expenses;
                    $Expenses->BOM_ID = $BOMID;
                    $Expenses->Other_Expenses = $res['Other_Expenses'][$key] ?? '';
                    $Expenses->Other_Expenses_Amount = $res['Other_Expenses_Amount'][$key] ?? '';

                    $Expenses->save();
                }
            }
        }

        if (isset($res['Transport']) && $res['Transport'] != '') {
            foreach ($res['Transport'] as $key => $val) {
                $TransportID = isset($res['TransportID'][$key]) ? $res['TransportID'][$key] : '';
                if ($TransportID != '') {
                    $Expenses = BOM_Transport::where('id', $TransportID)->update(['Transport' => $res['Transport'][$key] ?? '']);
                } else {
                    $Expenses = new BOM_Transport;
                    $Expenses->BOM_ID = $BOMID;
                    $Expenses->Transport = $res['Transport'][$key] ?? '';

                    $Expenses->save();
                }
            }
        }

        if (!isset($request->draft)) {
            $Approve_step = BOM::where('id', $BOMID)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = BOM::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = BOM::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = BOM_Approve::where('BOM_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new BOM_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->BOM_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('BOM/BOMList')->with('success', 'Added Successfully....');
    }
}
