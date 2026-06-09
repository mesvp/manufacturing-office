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

      $sql = "SELECT 
            psml.*,jb.jb_barcode as barCode,tbl_process_stage.stage_pos,COALESCE(mat1.material_name,'NA') AS bomMatName,mml.title AS matName
            FROM tbl_factory_jb_laravel AS jb
            LEFT JOIN tbl_factory_fqc_laravel AS fqc
            ON jb.jb_id = fqc.fqc_QC
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = jb.jb_batchNo
           LEFT JOIN tbl_process_stage ON psml.useStage = tbl_process_stage.stage_name
           LEFT JOIN prj_material AS mat1 ON mat1.id = psml.bomMat
           LEFT JOIN tbl_factory_material_master_laravel AS mml ON mml.id = psml.material
            WHERE (
            fqc.fqc_QC IS NULL AND (psml.useStage IS NULL OR tbl_process_stage.stage_pos <=5)
        ) 
        AND jb.status = '1'  
        ORDER BY `jb`.`jb_barCode` DESC";
        
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $data['pageTitle'] = 'Pending Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Pending Raw Material at Final QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-pending-report', $data);
    
    }

    public Function consumeRawMat(Request $request){
      $data['menu'] = 'fqc-consumeRawMat';

      $sql = "SELECT 
            psml.*,fqc.fqc_barcode as barCode,fqc.status,tbl_process_stage.stage_pos,COALESCE(mat1.material_name,'NA') AS bomMatName,mml.title AS matName
            FROM tbl_factory_fqc_laravel AS fqc
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = fqc.fqc_batchNo
           LEFT JOIN tbl_process_stage ON psml.useStage = tbl_process_stage.stage_name
           LEFT JOIN prj_material AS mat1 ON mat1.id = psml.bomMat
           LEFT JOIN tbl_factory_material_master_laravel AS mml ON mml.id = psml.material
            WHERE (psml.useStage IS NULL OR tbl_process_stage.stage_pos <=5)
        ORDER BY fqc.fqc_barcode DESC";
        
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $data['pageTitle'] = 'Consumed Raw Material at Final QC Stage List Details';
      $data['reportTitle'] = 'Consumed Raw Material at Final QC Stage';
        
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.RawMaterial.raw-material-consumed-report', $data);
    
    }
}
