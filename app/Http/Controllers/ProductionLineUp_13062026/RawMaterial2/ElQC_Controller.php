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
                                ->orWhere('tbl_process_stage.stage_pos', '<=', 2);
                      });
              });
        })
        ->groupBy('psml.batchNo','psml.material')
        ->orderBy('bol.bushing_barCode', 'DESC');

    // Execute pagination (15 items per page)
    $data['AllLists'] = $query->paginate(15);

      $data['pageTitle'] = 'Pending Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at ELQC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }

    public Function consumeRawMat(Request $request){
      $data['menu'] = 'elqc-consumeRawMat';

      $sql = "SELECT 
            psml.*,elqc.elqc_barcode as barCode,elqc.status,tbl_process_stage.stage_pos,COALESCE(mat1.material_name,'NA') AS bomMatName,mml.title AS matName
            FROM tbl_factory_el_qc_laravel AS elqc
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = elqc.elqc_batchNo
           LEFT JOIN tbl_process_stage ON psml.useStage = tbl_process_stage.stage_name
           LEFT JOIN prj_material AS mat1 ON mat1.id = psml.bomMat
           LEFT JOIN tbl_factory_material_master_laravel AS mml ON mml.id = psml.material
            WHERE (psml.useStage IS NULL OR tbl_process_stage.stage_pos <=2)
            GROUP BY psml.batchNo,psml.material
        ORDER BY elqc.elqc_barcode DESC";
        
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $data['pageTitle'] = 'Consumed Raw Material at ELQC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at ELQC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }
}
