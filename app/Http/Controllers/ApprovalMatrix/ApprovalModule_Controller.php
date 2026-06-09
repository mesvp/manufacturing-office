<?php

namespace App\Http\Controllers\ApprovalMatrix;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ApprovalMatrix\ApprovalModule_Model;

class ApprovalModule_Controller extends Controller
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
      $data['menu'] = 'approval-module';
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      $data['ModuleList'] = ApprovalModule_Model::all();

      return view('ApprovalMatrix.approval-module',$data);
    }
    else{
      return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
    }
  }
  public function insert(Request $request){
    if(request()->session()->has('empId')){
      print_r($_POST);
      $id = time();
      $data = array(
        'id'              => $id,
        'title'           => request()->input('module'),
        'tableName'       => request()->input('table')
      );

      $res = ApprovalModule_Model::create($data);

      if($res->exists){
        return redirect()->to(url('approval-matrix/approval-module'))->with('success', 'Module added Successfully');
      }else{
        return redirect()->to(url('approval-matrix/approval-module'))->with('failed', 'Something went wrong contact with System Aministrator');
      }
    }
    else{
      return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
    }
  }
}
