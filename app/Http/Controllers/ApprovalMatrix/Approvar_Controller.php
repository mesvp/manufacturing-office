<?php

namespace App\Http\Controllers\ApprovalMatrix;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ApprovalMatrix\ApprovalModule_Model;
use App\Models\ApprovalMatrix\ApprovalStage_Model;
use App\Models\ApprovalMatrix\Approver_Model;

class Approvar_Controller extends Controller
{
    
  public  static function PermittedMenuList($sessionId){
      //Menu Permission
      $res = DB::table('prod_menu_laravel')
      ->leftJoin('prod_menu_acc_laravel', 'prod_menu_laravel.id', '=', 'prod_menu_acc_laravel.menu_id')
      ->where('prod_menu_acc_laravel.emp_id', '=', $sessionId)
      ->where('prod_menu_acc_laravel.accessType', '=', 'yes')
      ->select('prod_menu_laravel.*', 'prod_menu_acc_laravel.accessType')
      ->get();
      
      return $res;
  }
  public function index(Request $request){
    if(request()->session()->has('empId')){
      $data['menu'] = 'approval-master';
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      $data['ModuleList'] = ApprovalModule_Model::all();
    
      if(isset($_GET['stage_module'])){
        $col = 'tbl_factory_appr_stage_laravel.stage_module';
        $val  = $_GET['stage_module'];
        $operator = '=';
      }else{
        $col = 'tbl_factory_appr_stage_laravel.stage_module';
        $val  = '';
        $operator = '<>';
      }
      
      $data['stageDetails'] = DB::table('tbl_factory_appr_stage_laravel')
      ->select('tbl_factory_appr_stage_laravel.*')
      ->orderBy('tbl_factory_appr_stage_laravel.stage_position','ASC')
      ->where($col,$operator,$val)
      ->get();
      
      $data['approverDetails'] = DB::table('tbl_factory_appr_laravel')
      ->select('tbl_factory_appr_laravel.*')
      ->join('tbl_factory_appr_stage_laravel','tbl_factory_appr_laravel.stage_id','=','tbl_factory_appr_stage_laravel.id')
      ->orderBy('tbl_factory_appr_laravel.created_at','DESC')
      ->where($col,$operator,$val)
      ->get();

      $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.*')
      ->get();
     // $data['userList'] = AdminUserModel::where('status','1')->get();

      return view('ApprovalMatrix.approval-master',$data);
    }
    else{
      return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
    }
  }


  public function insert(Request $request){
    if(request()->session()->has('empId')){


      $stage_module = $request->query('stage_module');
      $moduleName = $request->query('moduleName');
      if ($stage_module) {
          $queryParams['stage_module'] = $stage_module;
          $queryParams['moduleName'] = $moduleName;
      }


      
      $stage_name = request()->input('stage_name');
      $stage_pos = request()->input('stage_pos');
      $stage_id = request()->input('stage_id');
      $status = request()->input('status');


      foreach($stage_name as $key=>$stage){


        $count = ApprovalStage_Model::where('id', $stage_id[$key])->count();
        $index = $key+1;
        $stageEmps = request()->input('stage_emp_'.$index);

        if($count >0){
          $result = ApprovalStage_Model::where('id',$stage_id[$key]);
          $input['stage_stat'] = $status[$key];
          $res = $result->update($input);

          
          if($status[$key] == 0){
      
              $tableArr = DB::table('tbl_factory_appr_module_laravel')
              ->select('*')
              ->where('id',request()->input('stage_module'))
              ->first();

              $nextPositionIdObj = ApprovalStage_Model::where('id','>', $stage_id[$key])
              ->where('stage_module', request()->input('stage_module'))
              ->where('stage_stat', '1')
              ->orderBy('id','asc')
              ->limit(1)
              ->get();

              foreach($nextPositionIdObj as $nextPosition);

              if(count($nextPositionIdObj)>0){
                $nextStage = $nextPositionIdObj[0]['id'];


                  DB::table($tableArr->tableName)
                  ->where('stage', $stage_id[$key])
                  ->update([
                      'stage' => $nextStage
                  ]);


              }else{

                DB::table($tableArr->tableName)
                  ->where('stage', $stage_id[$key])
                  ->update([
                      'status' => 1
                  ]);

              }
              
              $result = ApprovalStage_Model::where('id',$stage_id[$key]);
              $input1['stage_stat'] = 0;
              $res = $result->update($input1);
            
            
          }

          Approver_Model::where('stage_id', $stage_id[$key])->delete();

          foreach($stageEmps as $stageEmp1){
            $data = array(
              "stage_id"       => $stage_id[$key],
              "person_id"      => $stageEmp1,
              "created_by"     => request()->session()->get('empId')
            );

            $res = Approver_Model::create($data);
          }


        }
        else{


          $data = array(
            "id"                => $stage_id[$key],
            "stage_title"       => $stage,
            "stage_module"      => request()->input('stage_module'),
            "stage_position"    => $stage_pos[$key],
            "created_by"        => request()->session()->get('empId')
          );
    
          $res = ApprovalStage_Model::create($data);

          

          foreach($stageEmps as $stageEmp){
            $data = array(
              "stage_id"       => $stage_id[$key],
              "person_id"      => $stageEmp,
              "created_by"     => request()->session()->get('empId')
            );

            $res = Approver_Model::create($data);
          }

            
        }
        

  
      }


      if($res->exists){
  
        return redirect()->to(url('approval-matrix/approval-master').'?'.http_build_query($queryParams))
        ->with('success', 'Approver added successfully.');
      }else{
        return redirect()->to(url('approval-matrix/approval-master').'?'.http_build_query($queryParams))
        ->with('failed', 'Something Went Wrong Please Contact with System Administrator.');
      }
      
      

    }
    else{
      return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
    }
  }
  
}
