<?php

namespace App\Http\Controllers\ProductionLineUp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard_Controller extends Controller
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
      $data['menu'] = "dashboard";

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

      $data['AllLists'] = $query->get();
      
      
      //Rejection Percentage Data
      
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
      
    
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.dashboard',$data);
    }
}
