<?php

namespace App\Http\Controllers\ProductionLineUp\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ninetydeg_Controller extends Controller
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
      $data['menu'] = '90deg-availRawMat';

      $data['AllLists'] = DB::table('tbl_factory_el_qc_laravel as elqc')
        ->select([
            'psml.*',
            DB::raw("SUM(psml.qty) AS totQty"),
            'elqc.elqc_barcode as barCode',
            'tbl_process_stage.stage_pos',
            DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
            'mml.title as matName'
        ])
        ->leftJoin('tbl_factory_ninetydeg_laravel as ninetydeg', 'elqc.elqc_id', '=', 'ninetydeg.ninetydeg_laminatorNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'elqc.elqc_batchNo')
        ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
        ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
        ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
        ->where(function($query) {
            $query->whereNull('ninetydeg.ninetydeg_laminatorNo')
                  ->orWhere(function($sub) {
                      $sub->where('ninetydeg.rwrk_status', '')
                          ->where('ninetydeg.status', '0')
                          ->where(function($stage) {
                              $stage->whereNull('psml.useStage')
                                    ->orWhere('tbl_process_stage.stage_pos', '=', 3);
                          });
                  });
        })
        ->where('elqc.status', '1')
        ->orderBy('elqc.elqc_barcode', 'DESC')
        ->groupBy('psml.batchNo', 'psml.material')
        ->paginate(10);

      $data['pageTitle'] = 'Pending Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at 90 Degree QC Stage';
      $data['detailsLink'] = 'production-lineup/raw-material-report/pending-raw-material-90deg/view-details';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }

    public Function availRawMatDtls(Request $request){
      $data['menu'] = '90deg-availRawMat';

      $data['AllLists'] = DB::table('tbl_factory_el_qc_laravel as elqc')
        ->select([
            'psml.*',
            'elqc.elqc_barcode as barCode',
            'tbl_process_stage.stage_pos',
            DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
            'mml.title as matName'
        ])
        ->leftJoin('tbl_factory_ninetydeg_laravel as ninetydeg', 'elqc.elqc_id', '=', 'ninetydeg.ninetydeg_laminatorNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'elqc.elqc_batchNo')
        ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
        ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
        ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
        ->where(function($query) {
            $query->whereNull('ninetydeg.ninetydeg_laminatorNo')
                  ->orWhere(function($sub) {
                      $sub->where('ninetydeg.rwrk_status', '')
                          ->where('ninetydeg.status', '0')
                          ->where(function($stage) {
                              $stage->whereNull('psml.useStage')
                                    ->orWhere('tbl_process_stage.stage_pos', '=', 3);
                          });
                  });
        })
        ->where('elqc.status', '1')
        ->where('elqc.elqc_batchNo', $_GET['batchno'])
        ->orderBy('elqc.elqc_barcode', 'DESC')
        ->get();

      $data['pageTitle'] = 'Pending Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at 90 Degree QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-details-report', $data);
    
    }

    public Function consumeRawMat(Request $request){
      $data['menu'] = '90deg-consumeRawMat';

      $data['AllLists'] = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
          ->select([
              'psml.*',
              DB::raw("SUM(psml.qty) AS totQty"),
              'ninetydeg.ninetydeg_barcode as barCode',
              'ninetydeg.status',
              'tbl_process_stage.stage_pos',
              DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
              'mml.title as matName'
          ])
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
          ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
          ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
          ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
          ->where(function($query) {
              $query->whereNull('psml.useStage')
                    ->orWhere('tbl_process_stage.stage_pos', '=', 3);
          })
          ->orderBy('ninetydeg.ninetydeg_barcode', 'DESC')
          ->groupBy('psml.batchNo', 'psml.material')
          ->paginate(10);

      $data['pageTitle'] = 'Consumed Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at 90 Degree QC Stage';
      
      $data['detailsLink'] = 'production-lineup/raw-material-report/consumed-raw-material-90deg/view-details';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }

    public Function consumeRawMatDtls(Request $request){
      $data['menu'] = '90deg-consumeRawMat';

      $data['AllLists'] = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
          ->select([
              'psml.*',
              'ninetydeg.ninetydeg_barcode as barCode',
              'ninetydeg.status',
              'tbl_process_stage.stage_pos',
              DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
              'mml.title as matName'
          ])
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
          ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
          ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
          ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
          ->where(function($query) {
              $query->whereNull('psml.useStage')
                    ->orWhere('tbl_process_stage.stage_pos', '=', 3);
          })
          ->where('ninetydeg.ninetydeg_batchNo', $_GET['batchno'])
          ->orderBy('ninetydeg.ninetydeg_barcode', 'DESC')
          
          ->get();

      $data['pageTitle'] = 'Consumed Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at 90 Degree QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-details-report', $data);
    
    }
}
