<?php

namespace App\Http\Controllers\ProductionLineUp\DashboardReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use App\Models\MaterialManagement\MaterialManagement_Add_Material;

class DailyProduction extends Controller
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
  //
  public function index(Request $request){
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->where('mstr_emp.status', '1')
      ->get();

    $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
		$FinishedGood = [];
		$i = 0;
		foreach ($MAT_DATA as $Val) {
			if (isset($Val->Raw_Material)) {
				//$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
				$Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
					->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
					->where('materialmanagement_add_material.id', $Val->Raw_Material)
					->first();

				$FinishedGood[$i]['matId'] = $Val->Raw_Material;
				$FinishedGood[$i]['matName'] = $Val->RawMaterial->matname;
				$i++;
			}
		}
    $data['FinishedGood'] = $FinishedGood;

    $query = DB::table('tbl_factory_production_setup_laravel as psl')
      ->select(
        'psl.batchNo',
        'psl.wattage',
        'fqc.fqc_date',
        'hr_mstr_shift.shift',
        'me.fullname',
        DB::raw('COUNT(fqc.fqc_batchNo) AS totalMatNo'),
        DB::raw('pm.material_name AS finish_good_name')
      )
      ->join('tbl_factory_fqc_laravel as fqc', function ($join) {
        $join->on('psl.batchNo', '=', 'fqc.fqc_batchNo');
      })
      ->join('mstr_emp as me', function ($join) {
        $join->on('fqc.fqc_incharge', '=', 'me.id');
      })
      ->leftJoin('materialmanagement_add_material as mmam', function ($join) {
        $join->on('psl.finishGood', '=', 'mmam.id');
      })
      ->leftJoin('prj_material as pm', function ($join) {
        $join->on('mmam.Material_Name', '=', 'pm.id');
      })
      ->join('hr_mstr_shift', function ($join) {
        $join->on('fqc.fqc_shift', '=', 'hr_mstr_shift.id');
      })
      ->where('fqc.status','1')
      ->groupBy('fqc.fqc_batchNo', 'fqc.fqc_shift');

      if ($request->filled('date')) {
        $query->whereDate('fqc.created_at', '=', $request->date);
      }else {
        $query->whereDate('fqc.created_at', '=', date('Y-m-d'));
      }
      if ($request->filled('checker')) {
        $query->where('fqc.fqc_incharge', $request->checker);
      }
      if ($request->filled('shift')) {
        $query->where('fqc.fqc_shift', $request->shift);
      }
      if ($request->filled('material')) {
        $query->where('psl.finishGood', $request->material);
      }

    $data['AllLists'] = $query->get();

    $data['menu'] = "daily-production";
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.DashboardReports.daily-production',$data);
  }
}
