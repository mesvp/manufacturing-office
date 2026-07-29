<?php

namespace App\Http\Controllers\ProductionLineUp\DashboardReports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use App\Models\MaterialManagement\MaterialManagement_Add_Material;

class RejectionPercentage extends Controller
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

    $query = DB::table('tbl_factory_bushing_laravel AS bushing')
        ->select('hr_mstr_shift.shift','bushing.bushing_date')
        ->selectRaw("
            SUM(CASE WHEN bushing.bushing_hasDamage = 'No' THEN 1 ELSE 0 END) AS Passed,
            SUM(CASE WHEN bushing.bushing_hasDamage <> 'No' THEN 1 ELSE 0 END) AS Rejected
        ")
        ->join('hr_mstr_shift', function ($join) {
          $join->on('bushing.bushing_shift', '=', 'hr_mstr_shift.id');
        });
    if ($request->filled('date')) {
        $query->whereDate('bushing.created_at', '=', $request->date);
    } else {
        $query->whereDate('bushing.created_at', '=', now()->toDateString()); // Clean Laravel helper for date('Y-m-d')
    }
    $data['Bushing'] = $query->get();


    $query = DB::table('tbl_factory_el_qc_laravel AS elqc')
        ->select('hr_mstr_shift.shift','elqc.elqc_date')
        ->selectRaw("
            SUM(CASE WHEN elqc.status = 1 THEN 1 ELSE 0 END) AS Passed,
            SUM(CASE WHEN elqc.status <> 1 THEN 1 ELSE 0 END) AS Rejected,
            SUM(CASE WHEN elqc.rwrk_status = 1 THEN 1 ELSE 0 END) AS Rework
        ")
        ->join('hr_mstr_shift', function ($join) {
          $join->on('elqc.elqc_shift', '=', 'hr_mstr_shift.id');
        });
    if ($request->filled('date')) {
        $query->whereDate('elqc.created_at', '=', $request->date);
    } else {
        $query->whereDate('elqc.created_at', '=', now()->toDateString()); // Clean Laravel helper for date('Y-m-d')
    }
    $data['EL'] = $query->get();


    $query = DB::table('tbl_factory_ninetydeg_laravel AS ninetydeg')
        ->select('hr_mstr_shift.shift','ninetydeg.ninetydeg_date')
        ->selectRaw("
            SUM(CASE WHEN ninetydeg.status = 1 THEN 1 ELSE 0 END) AS Passed,
            SUM(CASE WHEN ninetydeg.status <> 1 THEN 1 ELSE 0 END) AS Rejected,
            SUM(CASE WHEN ninetydeg.rwrk_status = 1 THEN 1 ELSE 0 END) AS Rework
        ")
        ->join('hr_mstr_shift', function ($join) {
          $join->on('ninetydeg.ninetydeg_shift', '=', 'hr_mstr_shift.id');
        });
    if ($request->filled('date')) {
        $query->whereDate('ninetydeg.created_at', '=', $request->date);
    } else {
        $query->whereDate('ninetydeg.created_at', '=', now()->toDateString()); // Clean Laravel helper for date('Y-m-d')
    }
    $data['Ninetydeg'] = $query->get();


    $query = DB::table('tbl_factory_jb_laravel AS jb')
        ->select('hr_mstr_shift.shift','jb.jb_date')
        ->selectRaw("
            SUM(CASE WHEN jb.status = 1 THEN 1 ELSE 0 END) AS Passed,
            SUM(CASE WHEN jb.status <> 1 THEN 1 ELSE 0 END) AS Rejected
        ")
        ->join('hr_mstr_shift', function ($join) {
          $join->on('jb.jb_shift', '=', 'hr_mstr_shift.id');
        });
    if ($request->filled('date')) {
        $query->whereDate('jb.created_at', '=', $request->date);
    } else {
        $query->whereDate('jb.created_at', '=', now()->toDateString()); // Clean Laravel helper for date('Y-m-d')
    }
    $data['JB'] = $query->get();


    $query = DB::table('tbl_factory_fqc_laravel AS fqc')
        ->select('hr_mstr_shift.shift','fqc.fqc_date')
        ->selectRaw("
            SUM(CASE WHEN fqc.status = 1 THEN 1 ELSE 0 END) AS Passed,
            SUM(CASE WHEN fqc.status <> 1 THEN 1 ELSE 0 END) AS Rejected
        ")
        ->join('hr_mstr_shift', function ($join) {
          $join->on('fqc.fqc_shift', '=', 'hr_mstr_shift.id');
        });
    if ($request->filled('date')) {
        $query->whereDate('fqc.created_at', '=', $request->date);
    } else {
        $query->whereDate('fqc.created_at', '=', now()->toDateString()); // Clean Laravel helper for date('Y-m-d')
    }
    $data['FQC'] = $query->get();

    $data['menu'] = "rejection-percentage";
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.DashboardReports.rejection-percentage',$data);
  }
}
