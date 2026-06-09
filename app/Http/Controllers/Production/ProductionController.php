<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\PlantStock;
use Illuminate\Http\Request;
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\SerialNumber\{FactorySerialNumber,FactorySerialNumberDetail};

use Session;

class ProductionController extends Controller
{
    public function  store(Request $request)
    {
         //return $request->all();
        //pre($request->input(),true);
        if (!isset($request->draft)) 
        {
            $required = [
                'Unit_Name' => 'required',
                'Plant_Name' => 'required',
                'Organization' => 'required',
                'BU' => 'required',
                'shift' => 'required',
                //'Raw_Material' => 'required',
                'Rate' => 'required',
                'Quantity' => 'required',
                //'Total_amount' => 'required',
            ];
            if($request->edit=='') $required['Raw_Material']='required';
            $request->validate($required);
        }
        if(isset($request->edit) && $request->edit!='' )
        {
            $prod=Production::where('id',$request->edit)->first();
            Production::where('id', $request->edit)->update(['Approve_status' => null]);
            ProductionApprove::where('productionID', $request->edit)->where('status', 1)->update(['status' => 0]);
        }
        else
        {
            $prod=new Production;
            $prod->userID=auth()->user()->id;
            if (!isset($request->draft))
            {
               $prod->Approve_Step = 1;
           } 
        }
        if (!isset($request->draft))
         {
            $prod->status = 0;
        } else 
        {
            $prod->status = 1;
        }
        $prod->Unit_Name=$request->Unit_Name;
        $prod->Plant_Name=$request->Plant_Name;
        $prod->Organization_Name=$request->Organization;
        $prod->UOM=$request->UOM;
        $prod->BU_Name=$request->BU;
        $prod->Shift=$request->shift;
        $prod->Production_Date=$request->Production_Date;
       if(isset($request->Raw_Material)) $prod->Raw_Material=$request->Raw_Material;
        $prod->Rate=$request->Rate;
        $prod->Quantity=$request->Quantity;
        $prod->Total_amount=($request->Quantity*$request->Rate);
        $prod->remarks=$request->remarks??'';
        $prod->save();
        $insert_id=$prod->id;
        $input=$request->input();
        if(isset($request->edit) && $request->edit!='')
        {
            ProductionData::where('productionID',$request->edit)->delete();
        }
        if(isset($input['materialID']))
        {
            foreach($input['materialID'] as $key=> $value)
            {
                //dd($value);
                $prod_data=new ProductionData;
                $prod_data->productionID=$insert_id;
                $prod_data->RawMaterial_id=$value;
                $prod_data->PlantStock=$input['Plantstock'][$key];
                $prod_data->UMO=$input['UMO'][$key];
                $prod_data->RawMaterialName=$input['Material_Name'][$key];
                $prod_data->ConsumtionQty=$input['Material_QTY'][$key];
                $prod_data->ScarpQty=$input['Scarp_QTY'][$key];
                $prod_data->OtherQty=$input['otherQTY'][$key]??0;
                $prod_data->TotalQty=($input['Material_QTY'][$key]+$input['Scarp_QTY'][$key]+$input['otherQTY'][$key]);
                $prod_data->save();
                //$checkmat=PlantStock::where('materialID',$value)->first();
                $checkmat = PlantStock::where([
                    'materialID' => $value,
                    'plantID' => $request->Plant_Name
                ])->first();
                if($checkmat){
                    PlantStock::stock($request->Plant_Name,$value, $prod_data->TotalQty,0);
                }else{
                    $plantdata = new PlantStock;
                    $plantdata->type = 0;
                    $plantdata->plantID = $request->Plant_Name;
                    $plantdata->materialID = $value;
                    $plantdata->Manufacturing_Unit = $request->Unit_Name;
                    $plantdata->stock = $input['Plantstock'][$key];
                    $plantdata->save();
                    
                }    
            }
        }
        if((!isset($request->edit) && $request->edit=='' ) && !isset($request->draft))
        {
            $batch=productionBatch::max('batch');
            if(empty($batch) || $batch==0)
            {
                $batch_no='BNO1';
                $batch=1;
            }
            else
            {
                //$str=str_replace("BNO","",$batch);
                $batch_no='BNO'.($batch+1);
                $batch=($batch+1);
            }
            //$sl="SLNO";
            $sl_no = productionBatch::max('sl');
            $sl_check = $request->serial_check;
            // return $sl_check[1];

            // Get the array keys
            $keys = array_keys($sl_check);

            // Loop through the quantity using the keys
            for ($i = 0; $i < $request->Quantity; $i++) {
                $batcdd = new productionBatch;
                $batcdd->productionID = $insert_id;
                $batcdd->batch_no = $batch_no;
                $batcdd->batch = $batch;
                $batcdd->sl_no = "SLNO" . ($sl_no + $i + 1);
                $batcdd->serail_check = $sl_check[$keys[$i]];
                $fetchserialnumber = FactorySerialNumberDetail::select('factory_serial_number_details.*')
                    ->leftJoin('factory_serial_numbers', 'factory_serial_number_details.sl_id', '=', 'factory_serial_numbers.id')
                    ->where('sl_no', $sl_check[$keys[$i]])
                    ->where('factory_serial_numbers.serial_date', $request->Production_Date)
                    ->where('factory_serial_numbers.Shift_Name', $request->shift)
                    ->first();
                if (isset($fetchserialnumber)) {
                    FactorySerialNumberDetail::where('sl_no', $fetchserialnumber->sl_no)->update(['status' => 'USED']);
                }
                $batcdd->sl = $sl_no + $i + 1;
                $batcdd->save();
            }
        }
        return redirect('Production/ProductionList')->with('success', 'Added Successfully....');
    }
    public function AddSales(Request $request)
    {
        if ($request->edit) {
            $Sales = Production_For_Sales::find($request->edit);
        } else {
            $Sales = new Production_For_Sales;
            $Sales->userID = auth()->user()->id;
        }
        $Sales->Work_Order_Type = 'Sales';
        $Sales->Sales_Order_No = $request->Sales_Order_No;
        $Sales->Organization = $request->Organization;
        $Sales->BU = $request->BU;
        $Sales->Company_Name = $request->Company_Name;
        $Sales->Expected_Date = $request->Expected_Date;
        $Sales->Unit_Name = $request->Unit_Name;
        $Sales->Plant_Name = $request->Plant_Name;
        $Sales->Work_Order_Status = $request->Work_Order_Status;
        $Sales->remarks = $request->remarks;

        $Sales->save();

        $Sales->Work_Order_No = 'WON' . str_pad($Sales->id, 4, '0', STR_PAD_LEFT) . 'SALES';
        $Sales->Work_Order_Name = 'WON' . str_pad($Sales->id, 4, '0', STR_PAD_LEFT) . 'SALES';
        $Sales->save();

        return redirect('Production/ProductionList')->with('success', 'Added Successfully....');
    }

    public function AddStock(Request $request)
    {
        if ($request->edit) {
            $Stock = Production_For_Stock::find($request->edit);
        } else {
            $Stock = new Production_For_Stock;
            $Stock->userID = auth()->user()->id;
        }
        $Stock->Work_order_Type = 'Stock';
        $Stock->Stock_Order_No = $request->Stock_Order_No;
        $Stock->Organization = $request->Organization;
        $Stock->BU = $request->BU;
        $Stock->Company_Name = $request->Company_Name;
        $Stock->Expected_Date = $request->Expected_Date;
        $Stock->Unit_Name = $request->Unit_Name;
        $Stock->Plant_Name = $request->Plant_Name;
        $Stock->Work_Order_Status = $request->Work_Order_Status;
        $Stock->remarks = $request->remarks;

        $Stock->save();

        $Stock->Work_Order_No = 'WON' . str_pad($Stock->id, 4, '0', STR_PAD_LEFT) . 'STOCK';
        $Stock->Work_Order_Name = 'WON' . str_pad($Stock->id, 4, '0', STR_PAD_LEFT) . 'STOCK';
        $Stock->save();

        return redirect('Production/ProductionList')->with('success', 'Added Successfully....');
    }
}
