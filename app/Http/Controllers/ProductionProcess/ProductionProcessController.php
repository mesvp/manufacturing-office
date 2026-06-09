<?php

namespace App\Http\Controllers\ProductionProcess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionProcess\{Production_Process, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Machine, Production_Process_Approve};

class ProductionProcessController extends Controller
{
    public function AddProductionProcess(Request $request)
    {
        if (!isset($request->draft)) {
            $request->validate([
                'Description' => 'required',
                'Raw_Material' => 'required',
                'HSN_Code' => 'required',
                'UOM' => 'required',
            ]);
        }

        if ($request->edit != '') {
            $ProductProcess = Production_Process::find($request->edit);
        } else {
            $ProductProcess = new Production_Process;
            $ProductProcess->userID = auth()->user()->id;
            $ProductProcess->Forward_Status = 0;
        }
        $ProductProcess->Product = $request->Product;
        $ProductProcess->Sub_Product = $request->Sub_Product;
        $ProductProcess->Sub_Sub_Product = $request->Sub_Sub_Product;
        $ProductProcess->Raw_Material = $request->Raw_Material;
        $ProductProcess->HSN_Code = $request->HSN_Code;
        $ProductProcess->UOM = $request->UOM;
        $ProductProcess->Description = $request->Description;
        $ProductProcess->status = 1;


        $ProductProcess->save();
        if (isset($request->draft)) {
            return redirect('ProductionProcess/ProductionProcessList')->with('success', 'Data Drafted');
        } else {
            return redirect('ProductionProcess/ProductStage/' . $ProductProcess->id)->with('success', 'Added Successfully...');
        }
    }

    public function AddStages(Request $request)
    {
        //return $request->all();
        if (!isset($request->draft)) {
            $request->validate([
                'Process_Stage.*' => 'required',
                'Process_Name.*' => 'required',
                'Material_Use.*' => 'required',
                'Output.*' => 'required',
                'Description_Second.*' => 'required',
                'Material_Name.*' => 'required',
                'HSN_Code.*' => 'required',
                'UOM.*' => 'required',
                'Material_QTY.*' => 'required',
            ]);
        }

        if ($request->edit != '') {
            $Product = Production_Process::find($request->edit);
        } else {
            $Product = new Production_Process;
            $Product->userID = auth()->user()->id;
        }
        $Product->remarks = $request->remarks;
        if (!isset($request->draft)) {
            $Product->status = 0;
        } else {
            $Product->status = 1;
        }

        $Product->save();

        if ($request->edit != '') {
            $Product_ID = $request->edit;
        } else {
            $Product_ID =  $Product->id;
        }

        $res = $request->input();

        if (isset($res['Process_Stage']) && $res['Process_Stage'] != '') {
            foreach ($res['Process_Stage'] as $key => $val) {
                $Stage_Id = isset($res['Stage_Id'][$key]) ? $res['Stage_Id'][$key] : '';
                if ($Stage_Id != '') {
                    $Stage = Production_Process_Stage::where('id', $Stage_Id)->update(['Process_Stage' => $res['Process_Stage'][$key] ?? '', 'Parameter' => $res['Parameter'][$key] ?? '', 'UOM_Second' => $res['UOM_Second'][$key] ?? '']);
                } else {
                    $Stage = new Production_Process_Stage;
                    $Stage->Production_Process_Id = $Product_ID;
                    $Stage->Process_Stage = $res['Process_Stage'][$key] ?? '';
                    $Stage->Parameter = $res['Parameter'][$key] ?? '';
                    $Stage->UOM_Second = $res['UOM_Second'][$key] ?? '';

                    $Stage->save();
                }

                if (isset($res['Stage_Id'][$key]) && $res['Stage_Id'][$key] != '') {
                    $StageID = $res['Stage_Id'][$key];
                } else {
                    $StageID = $Stage->id;
                }

                if (isset($res['Process_Name'][$key]) && $res['Process_Name'][$key] != '') {
                    foreach ($res['Process_Name'][$key] as $key1 => $val) {
                        $StageData_Id = isset($res['StageData_Id'][$key][$key1]) ? $res['StageData_Id'][$key][$key1] : '';
                        if ($StageData_Id != '') {
                            $StageData = Production_Process_Stage_Data::where('id', $StageData_Id)->update(['Process_Name' => $res['Process_Name'][$key][$key1] ?? '', 'Material_Use' => $res['Material_Use'][$key][$key1] ?? '', 'Output' => $res['Output'][$key][$key1] ?? '', 'Description_Second' => $res['Description_Second'][$key][$key1] ?? '']);
                        } else {
                            $StageData = new Production_Process_Stage_Data;
                            $StageData->Production_Process_Stage_Id = $StageID;
                            $StageData->Process_Name = $res['Process_Name'][$key][$key1] ?? '';
                            $StageData->Material_Use = $res['Material_Use'][$key][$key1] ?? '';
                            $StageData->Output = $res['Output'][$key][$key1] ?? '';
                            $StageData->Description_Second = $res['Description_Second'][$key][$key1] ?? '';

                            $StageData->save();
                        }

                        if (isset($res['StageData_Id'][$key][$key1]) && $res['StageData_Id'][$key][$key1] != '') {
                            $StageDataID = $res['StageData_Id'][$key][$key1];
                        } else {
                            $StageDataID = $StageData->id;
                        }

                        if (isset($res['Material_Name'][$key][$key1]) && $res['Material_Name'][$key][$key1] != '') {
                            foreach ($res['Material_Name'][$key][$key1] as $key2 => $val) {
                                $Machine_Id = isset($res['Machine_Id'][$key][$key1][$key2]) ? $res['Machine_Id'][$key][$key1][$key2] : '';
                                if ($Machine_Id != '') {
                                    $Machine = Production_Process_Machine::where('id', $Machine_Id)->update(['Machine_Name' => $res['Machine_Name'][$key][$key1][$key2] ?? '', 'Machine_Code' => $res['Machine_Code'][$key][$key1][$key2] ?? '', 'Machine_Company' => $res['Machine_Company'][$key][$key1][$key2] ?? '', 'Make_Model' => $res['Make_Model'][$key][$key1][$key2] ?? '', 'Date_of_Purchase' => $res['Date_of_Purchase'][$key][$key1][$key2] ?? '', 'Preventive_Maintenance' => $res['Preventive_Maintenance'][$key][$key1][$key2] ?? '', 'Material_Name' => $res['Material_Name'][$key][$key1][$key2] ?? '', 'HSN_Code' => $res['HSN_Code'][$key][$key1][$key2] ?? '', 'UOM' => $res['UOM'][$key][$key1][$key2] ?? '', 'Material_QTY' => $res['Material_QTY'][$key][$key1][$key2] ?? '']);
                                } else {
                                    $Machine = new Production_Process_Machine;
                                    $Machine->Production_Process_Stage_Data_Id = $StageDataID;
                                    $Machine->Machine_Name = $res['Machine_Name'][$key][$key1][$key2] ?? '';
                                    $Machine->Machine_Code = $res['Machine_Code'][$key][$key1][$key2] ?? '';
                                    $Machine->Machine_Company = $res['Machine_Company'][$key][$key1][$key2] ?? '';
                                    $Machine->Make_Model = $res['Make_Model'][$key][$key1][$key2] ?? '';
                                    $Machine->Date_of_Purchase = $res['Date_of_Purchase'][$key][$key1][$key2] ?? '';
                                    $Machine->Preventive_Maintenance = $res['Preventive_Maintenance'][$key][$key1][$key2] ?? '';
                                    $Machine->Material_Name = $res['Material_Name'][$key][$key1][$key2] ?? '';
                                    $Machine->HSN_Code = $res['HSN_Code'][$key][$key1][$key2] ?? '';
                                    $Machine->UOM = $res['UOM'][$key][$key1][$key2] ?? '';
                                    $Machine->Material_QTY = $res['Material_QTY'][$key][$key1][$key2] ?? '';

                                    $Machine->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!isset($request->draft)) {
            $Approve_step = Production_Process::where('id', $Product_ID)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = Production_Process::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = Production_Process::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = Production_Process_Approve::where('Production_Process_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new Production_Process_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->Production_Process_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('ProductionProcess/ProductionProcessList')->with('success', 'Added Successfully....');
    }
}
