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

      $sql = "SELECT 
            psml.*,elqc.elqc_barcode as barCode,tbl_process_stage.stage_pos,COALESCE(mat1.material_name,'NA') AS bomMatName,mml.title AS matName
            FROM tbl_factory_el_qc_laravel AS elqc
            LEFT JOIN tbl_factory_ninetydeg_laravel AS ninetydeg
            ON elqc.elqc_id = ninetydeg.ninetydeg_laminatorNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = elqc.elqc_batchNo
           LEFT JOIN tbl_process_stage ON psml.useStage = tbl_process_stage.stage_name
           LEFT JOIN prj_material AS mat1 ON mat1.id = psml.bomMat
           LEFT JOIN tbl_factory_material_master_laravel AS mml ON mml.id = psml.material
            WHERE (
            ninetydeg.ninetydeg_laminatorNo IS NULL
            OR (ninetydeg.rwrk_status = '' AND ninetydeg.status = '0') AND (psml.useStage IS NULL OR tbl_process_stage.stage_pos <=3)
        ) 
        AND elqc.status = '1'  
        ORDER BY `elqc`.`elqc_barcode` DESC";
        
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $data['pageTitle'] = 'Pending Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at 90 Degree QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }

    public Function consumeRawMat(Request $request){
      $data['menu'] = '90deg-consumeRawMat';

      $sql = "SELECT 
            psml.*,ninetydeg.ninetydeg_barcode as barCode,ninetydeg.status,tbl_process_stage.stage_pos,COALESCE(mat1.material_name,'NA') AS bomMatName,mml.title AS matName
            FROM tbl_factory_ninetydeg_laravel AS ninetydeg
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = ninetydeg.ninetydeg_batchNo
           LEFT JOIN tbl_process_stage ON psml.useStage = tbl_process_stage.stage_name
           LEFT JOIN prj_material AS mat1 ON mat1.id = psml.bomMat
           LEFT JOIN tbl_factory_material_master_laravel AS mml ON mml.id = psml.material
            WHERE (psml.useStage IS NULL OR tbl_process_stage.stage_pos <=3)
        ORDER BY ninetydeg.ninetydeg_barcode DESC";
        
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $data['pageTitle'] = 'Consumed Raw Material at 90 Degree QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at 90 Degree QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }
}
