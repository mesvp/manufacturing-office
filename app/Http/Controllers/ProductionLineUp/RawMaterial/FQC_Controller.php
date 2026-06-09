<?php

namespace App\Http\Controllers\ProductionLineUp\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FQC_Controller extends Controller
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

    public Function availRawMat(Request $request){
      $data['menu'] = 'fqc-availRawMat';

     $data['AllLists'] = DB::table('tbl_factory_jb_laravel as jb')
    ->select(
        'psml.*',
        DB::raw("SUM(psml.qty) AS totQty"),
        'jb.jb_barcode as barCode',
        'tbl_process_stage.stage_pos',
        DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
        'mml.title as matName'
    )
    ->leftJoin('tbl_factory_fqc_laravel as fqc', 'jb.jb_id', '=', 'fqc.fqc_QC')
    ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'jb.jb_batchNo')
    ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
    ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
    ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
    ->where(function($query) {
        $query->whereNull('fqc.fqc_QC')
              ->where(function($sub) {
                  $sub->whereNull('psml.useStage')
                      ->orWhere('tbl_process_stage.stage_pos', '=', 5);
              });
    })
    ->where('jb.status', '1')
    ->orderBy('jb.jb_barCode', 'DESC')
    ->groupBy('psml.batchNo', 'psml.material')
    ->paginate(10);

      $data['pageTitle'] = 'Pending Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at Final QC Stage';
      $data['detailsLink'] = 'production-lineup/raw-material-report/pending-raw-material-fqc/view-details';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }


    
    public Function availRawMatDtls(Request $request){
      $data['menu'] = 'fqc-availRawMat';

     $data['AllLists'] = DB::table('tbl_factory_jb_laravel as jb')
    ->select(
        'psml.*',
        'jb.jb_barcode as barCode',
        'tbl_process_stage.stage_pos',
        DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
        'mml.title as matName'
    )
    ->leftJoin('tbl_factory_fqc_laravel as fqc', 'jb.jb_id', '=', 'fqc.fqc_QC')
    ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'jb.jb_batchNo')
    ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
    ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
    ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
    ->where(function($query) {
        $query->whereNull('fqc.fqc_QC')
              ->where(function($sub) {
                  $sub->whereNull('psml.useStage')
                      ->orWhere('tbl_process_stage.stage_pos', '=', 5);
              });
    })
    ->where('jb.status', '1')
    ->where('jb.jb_batchNo', $_GET['batchno'])
    ->orderBy('jb.jb_barCode', 'DESC')
    ->get();

      $data['pageTitle'] = 'Pending Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at Final QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-details-report', $data);
    
    }

    public Function consumeRawMat(Request $request){
      $data['menu'] = 'fqc-consumeRawMat';

     $data['AllLists'] = DB::table('tbl_factory_fqc_laravel as fqc')
      ->select([
          'psml.*',
          DB::raw("SUM(psml.qty) AS totQty"),
          'fqc.fqc_barcode as barCode',
          'fqc.status',
          'tbl_process_stage.stage_pos',
          DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
          'mml.title as matName'
      ])
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'fqc.fqc_batchNo')
      ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
      ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
      ->where(function($query) {
          $query->whereNull('psml.useStage')
                ->orWhere('tbl_process_stage.stage_pos', '=', 5);
      })
      ->where('fqc.status', '<>', 1)
      ->orderBy('fqc.fqc_barcode', 'DESC')
      ->groupBy('psml.batchNo', 'psml.material')
      ->paginate(10);

      $data['pageTitle'] = 'Consumed Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at Final QC Stage';
      
      $data['detailsLink'] = 'production-lineup/raw-material-report/consumed-raw-material-fqc/view-details';

      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }

    public Function consumeRawMatDtls(Request $request){
      $data['menu'] = 'fqc-consumeRawMat';

     $data['AllLists'] = DB::table('tbl_factory_fqc_laravel as fqc')
      ->select([
          'psml.*',
          'fqc.fqc_barcode as barCode',
          'fqc.status',
          'tbl_process_stage.stage_pos',
          DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
          'mml.title as matName'
      ])
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'fqc.fqc_batchNo')
      ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
      ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
      ->where(function($query) {
          $query->whereNull('psml.useStage')
                ->orWhere('tbl_process_stage.stage_pos', '=', 5);
      })
      ->where('fqc.status', '<>', 1)
      ->where('fqc.fqc_batchNo', $_GET['batchno'])
      ->orderBy('fqc.fqc_barcode', 'DESC')
      ->paginate(10);

      $data['pageTitle'] = 'Consumed Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at Final QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-details-report', $data);
    
    }
}
