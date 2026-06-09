<?php

namespace App\Http\Controllers\ProductionLineUp\RawMaterial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElQC_Controller extends Controller
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
      $data['menu'] = 'elqc-availRawMat';

      // Build the query
    $query = DB::table('tbl_factory_bushing_laravel as bol')
        ->select([
            'psml.*',
            DB::raw("SUM(psml.qty) AS totQty"),
            'bol.bushing_barCode as barCode',
            'tbl_process_stage.stage_pos',
            DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
            'mml.title as matName'
        ])
        ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'bol.bushing_batchNo')
        ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
        ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
        ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
        // Filter for non-damaged items
        ->where('bol.bushing_hasDamage', 'No')
        // Complex WHERE logic: (Null QC OR (Active Status AND Correct Stage))
        ->where(function($q) {
            $q->whereNull('elqc.elqc_bushingNo')
              ->orWhere(function($sub) {
                  $sub->where('elqc.rwrk_status', '')
                      ->where('elqc.status', '0')
                      ->where(function($inner) {
                          $inner->whereNull('psml.useStage')
                                ->orWhere('tbl_process_stage.stage_pos', '=', 2);
                      });
              });
        })
        ->orderBy('bol.bushing_barCode', 'DESC')
        ->groupBy('psml.batchNo', 'psml.material');

    // Execute pagination (15 items per page)
    $data['AllLists'] = $query->paginate(10);

      $data['pageTitle'] = 'Pending Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at ELQC Stage';
      
      $data['detailsLink'] = 'production-lineup/raw-material-report/pending-raw-material-elqc/view-details';
        
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }

    
    public Function availRawMatDtls(Request $request){
      $data['menu'] = 'elqc-availRawMat';

      // Build the query
    $query = DB::table('tbl_factory_bushing_laravel as bol')
        ->select([
            'psml.*',
            'bol.bushing_barCode as barCode',
            'tbl_process_stage.stage_pos',
            DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
            'mml.title as matName'
        ])
        ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'bol.bushing_batchNo')
        ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
        ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
        ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
        // Filter for non-damaged items
        ->where('bol.bushing_hasDamage', 'No')
        ->where('bol.bushing_batchNo', $_GET['batchno'])
        // Complex WHERE logic: (Null QC OR (Active Status AND Correct Stage))
        ->where(function($q) {
            $q->whereNull('elqc.elqc_bushingNo')
              ->orWhere(function($sub) {
                  $sub->where('elqc.rwrk_status', '')
                      ->where('elqc.status', '0')
                      ->where(function($inner) {
                          $inner->whereNull('psml.useStage')
                                ->orWhere('tbl_process_stage.stage_pos', '=', 2);
                      });
              });
        })
        ->orderBy('bol.bushing_barCode', 'DESC');

        $data['AllLists'] = $query->get();

    // Execute pagination (15 items per page)
    //$data['AllLists'] = $query->paginate(10);

      $data['pageTitle'] = 'Pending Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at ELQC Stage';

      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-details-report', $data);
    
    }


    public Function consumeRawMat(Request $request){
      $data['menu'] = 'elqc-consumeRawMat';

    $data['AllLists'] = DB::table('tbl_factory_el_qc_laravel as elqc')
    ->select([
        'psml.*',
        DB::raw("SUM(psml.qty) AS totQty"),
        'elqc.elqc_barcode as barCode',
        'elqc.status',
        'tbl_process_stage.stage_pos',
        DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
        'mml.title as matName'
    ])
    ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'elqc.elqc_batchNo')
    ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
    ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
    ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
    ->where(function($query) {
        $query->whereNull('psml.useStage')
              ->orWhere('tbl_process_stage.stage_pos', '=', 2);
    })
    ->orderBy('elqc.elqc_barcode', 'DESC')
    ->groupBy('psml.batchNo', 'psml.material')
    ->paginate(10);
      $data['pageTitle'] = 'Consumed Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at ELQC Stage';
      $data['detailsLink'] = 'production-lineup/raw-material-report/consumed-raw-material-elqc/view-details';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }


    public Function consumeRawMatDtls(Request $request){
      $data['menu'] = 'elqc-consumeRawMat';

    $data['AllLists'] = DB::table('tbl_factory_el_qc_laravel as elqc')
    ->select([
        'psml.*','elqc.elqc_barcode as barCode',
        'elqc.status',
        'tbl_process_stage.stage_pos',
        DB::raw("COALESCE(mat1.material_name, 'NA') AS bomMatName"),
        'mml.title as matName'
    ])
    ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'elqc.elqc_batchNo')
    ->leftJoin('tbl_process_stage', 'psml.useStage', '=', 'tbl_process_stage.stage_name')
    ->leftJoin('prj_material as mat1', 'mat1.id', '=', 'psml.bomMat')
    ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
    ->where('elqc.elqc_batchNo', $_GET['batchno'])
    ->where(function($query) {
        $query->whereNull('psml.useStage')
              ->orWhere('tbl_process_stage.stage_pos', '=', 2);
    })
    ->orderBy('elqc.elqc_barcode', 'DESC')
    ->paginate(10);
      $data['pageTitle'] = 'Consumed Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at ELQC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-details-report', $data);
    
    }
}
