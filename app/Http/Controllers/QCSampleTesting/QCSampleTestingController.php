<?php

namespace App\Http\Controllers\QCSampleTesting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QCSampleTesting\{QCFinishedGoodApprove,STDFinishedGoods, STDFinishedGoods_data, STDRawMaterial,QCFinishedGoodResult,QCFinishedGood};
use Session;

class QCSampleTestingController extends Controller
{
    public function recheck($request,$id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();
        //$approvesss = QCFinishedGoodApprove::where('QCFinishedGoodID', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        //$factory =  QCFinishedGood::where('id', $id)->update(['Approve_status' => null]);
        $approve = new QCFinishedGoodApprove;
        $approve->userID = auth()->user()->id;
        $approve->role = 'Inputer';
        $approve->QCFinishedGoodID = $id;
        $approve->status = 1;
        $approve->action = 'Checked';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();


       // return redirect('QCSampleTesting/STDFinishedGoodsList')->with('success', 'Hold Released successfully.....');
    }
    public function AddSTDFinishedGoods(Request $request)
    {
        $userid=auth()->user()->id;
        if(!isset($request->draft))
        {
            $request->validate([
                'Organization' => 'required',
                'productionID' => 'required',
                'Unit_Name' => 'required',
                'Plant_Name' => 'required',
                'BU' => 'required',
                'Raw_Material' => 'required',
                'SampleCollectedBy' => 'required',
                'batch_no' => 'required',
                'sl_no.*' => 'required',
                'result.*' => 'required',
            ]);
        }
        //pre($request->input(),true);
        if(isset($request->edit))
        {
            //echo $request->edit;
            //die;
            $qcfg=QCFinishedGood::where('id',$request->edit)->first();
            $qcfg->Approve_status=null;
            $this->recheck($request,$request->edit);
        }
        else
        {
            $qcfg= new QCFinishedGood;
            $qccode=QCFinishedGood::max('QCCode');
            $qcfg->Approve_Step=1;
            $qcfg->userID=$userid;
            // if(empty($qccode))
            // {
            //     $qccode='QC01';
            // }
            // else
            // {
            //     $str=str_replace("QC","",$qccode);
            //     $qccode='QC'.($str+1);
            // }
        }
        $qcfg->Unit_Name=$request->Unit_Name;
        $qcfg->Plant_Name=$request->Plant_Name;
        $qcfg->Raw_Material=$request->Raw_Material;
        $qcfg->Organization_Name=$request->Organization;
        $qcfg->BU_Name=$request->BU;
        $qcfg->productionID=$request->productionID;
        $qcfg->batch_no=$request->batch_no;
        $qcfg->SampleCollectedBy=$request->SampleCollectedBy;
        $qcfg->QCDate=$request->QCDate;
       
        $qcfg->remarks=$request->remark;
        $qcfg->save();
        $input=$request->input();
        if($request->edit=='')
        {
            QCFinishedGood::where('id',$qcfg->id)->update(['QCCode'=>("QC".$qcfg->id)]);
            
        }
        if(isset($request->edit))
        {
            QCFinishedGoodResult::where('QCFinishedGoodID',$request->edit)->delete();
        }
        if(isset($input['sl_no']))
        {
            foreach($input['sl_no'] as $key=> $value)
            {
                $qcfgr= new QCFinishedGoodResult;
                $qcfgr->QCFinishedGoodID=$qcfg->id;
                $qcfgr->productionID=$request->productionID;
                $qcfgr->production_batchID=$key;
                $qcfgr->batch_no=$request->batch_no;
                $qcfgr->sl_no=$input['sl_no'][$key];
                $qcfgr->result=$input['result'][$key];
                $qcfgr->remark=$input['remarks'][$key];
                $qcfgr->save();

            }
        }
        
    


        return redirect('QCSampleTesting/STDFinishedGoodsList')->with('success', 'Added Successfully....');
    }



    public function AddSTDRawMaterial(Request $request)
    {
        $request->validate([
            'Invoice_no' => 'required',
            'PO_NO' => 'required',
            'Material_Code' => 'required',
            'Material_Name' => 'required',
            'Material_Type' => 'required',
            'HNS_Code' => 'required',
            'QC_Status' => 'required',
            'Parameter_one' => 'required',
            'remarks_one' => 'required',
            'Parameter_two' => 'required',
            'Result_two' => 'required',
            'remarks_two' => 'required',
        ]);

        if ($request->edit != '') {
            $STD = STDRawMaterial::where('id', $request->edit)->update(['Invoice_no' => $request->Invoice_no, 'PO_NO' => $request->PO_NO, 'Material_Code' => $request->Material_Code, 'Material_Name' => $request->Material_Name, 'Material_Type' => $request->Material_Type, 'HNS_Code' => $request->HNS_Code, 'QC_Status' => $request->QC_Status, 'Parameter_one' => $request->Parameter_one, 'result_one' => $request->result_one, 'remarks_one' => $request->remarks_one, 'Parameter_two' => $request->Parameter_two, 'Result_two' => $request->Result_two, 'remarks_two' => $request->remarks_two, 'remarks' => $request->remarks]);
        } else {
            $STD = new STDRawMaterial;
            $STD->userID = auth()->user()->id;
            $STD->Invoice_no = $request->Invoice_no;
            $STD->PO_NO = $request->PO_NO;
            $STD->Material_Code = $request->Material_Code;
            $STD->Material_Name = $request->Material_Name;
            $STD->Material_Type = $request->Material_Type;
            $STD->HNS_Code = $request->HNS_Code;
            $STD->QC_Status = $request->QC_Status;
            $STD->Parameter_one = $request->Parameter_one;
            $STD->result_one = $request->result_one;
            $STD->remarks_one = $request->remarks_one;
            $STD->Parameter_two = $request->Parameter_two;
            $STD->Result_two = $request->Result_two;
            $STD->remarks_two = $request->remarks_two;
            $STD->remarks = $request->remarks;

            $STD->save();
        }

        return redirect('QCSampleTesting/STDRawMaterialList')->with('success', 'Added Successfully....');
    }
}
