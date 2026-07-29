<?php

namespace App\Http\Controllers\ProductionLineUp\BushingSetup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategories\{ProductCategories_Add_Product};
use App\Models\MaterialManagement\MaterialManagement_Add_Material;
use App\Models\BOM\{BOM, BOM_Material, BOM_Approve};
use App\Models\ProductionLineUp\Bushing_Model;
use App\Models\ProductionLineUp\BushingMaterial_Model;
use App\Models\ProductionLineUp\BushingDamageMaterial_Model;
use Session;
use PDF;

class BushingSetup_Controller extends Controller
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
  
  
  public function getUserIP()
  {
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
      $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
      $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
      $ip = $forward;
    } else {
      $ip = $remote;
    }

    return $ip;
  }

  public function getBushingMaterial(Request $request)
  {
    $batchNo = $request->query('q');

    $batchData = DB::table('tbl_factory_production_setup_laravel as psl')
      ->select(
        'psl.wattage',
        'psml.material AS mid',
        'psml.size AS msize',
        'psml.qty AS mqty',
        'psml.brand AS mbrand',
        'mml.title as mname',
        'materialmanagement_add_material.id AS pid',
        'prj_material.material_name as matname'
      )
      ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
      ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
      ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
      ->orderBy('psl.created_at', 'DESC')
      ->where('psl.batchNo', $batchNo)
      ->get();
    foreach ($batchData as $batch)

      $materials[] = [
        'mid' => $batch->mid ?? 'N/A',
        'mname' => $batch->mname ?? 'N/A',
        'msize' => $batch->msize ?? 'N/A',
        'mqty' => $batch->mqty ?? 'N/A',
        'mbrand' => $batch->mbrand ?? 'N/A',
      ];
    Session::put('bushing_finishGood', $batch->pid ?? 'N/A');
    return response()->json([
      'wattage' => $batch->wattage ?? 'N/A',
      'pid' => $batch->pid ?? 'N/A',
      'matname' => $batch->matname ?? 'N/A',
      "materials" => $materials
    ]);
  }

  public function index()
  {
    $data['menu'] = 'bushing-setup';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $Cond = [];
    $Condition = 'psml.material = 1';

    if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
      $Cond[] = "bol.created_by = '" . $_GET['createdBy'] . "'";
    }
    if (isset($_GET['operator']) && $_GET['operator'] != '') {
      $Cond[] = "bol.bushing_operator = '" . $_GET['operator'] . "'";
    }
    if (isset($_GET['checker']) && $_GET['checker'] != '') {
      $Cond[] = "bol.bushing_incherge = '" . $_GET['checker'] . "'";
    }
    if (isset($_GET['shift']) && $_GET['shift'] != '') {
      $Cond[] = "bol.bushing_shift = '" . $_GET['shift'] . "'";
    }
    if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
    }
    if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
    }
    if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $_GET['batchNo'] . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }

    $sql = "SELECT 
    bol.*,
    psl.wattage,
    psml.size AS cellSize,
    psml.brand,
    sh.shift AS shiftdtl,
    a.fullname AS bushing_operator,
    b.fullname AS bushing_incherge,
    c.fullname AS createdBy
    FROM tbl_factory_bushing_laravel AS bol
    LEFT JOIN hr_mstr_shift AS sh 
        ON sh.id = bol.bushing_shift
    LEFT JOIN tbl_factory_production_setup_laravel AS psl 
        ON psl.batchNo = bol.bushing_batchNo
    LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
        ON psml.batchNo = psl.batchNo
    LEFT JOIN mstr_emp AS a 
        ON bol.bushing_operator = a.id
    LEFT JOIN mstr_emp AS b 
        ON bol.bushing_incherge = b.id
    LEFT JOIN mstr_emp AS c 
        ON bol.created_by = c.id
    WHERE $Condition
    ORDER BY bol.created_at DESC";
    // dd($sql);
    $data['AllLists'] = DB::select($sql);

    // dd($data['AllLists']);
    
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-setup', $data);
  }

  public function add_bushing_setup(Request $request)
  {
      
    $data['menu'] = 'bushing-setup';
    $data['batchList'] = DB::table('tbl_factory_production_setup_laravel as psl')
      ->select('psl.batchNo')
      ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
      ->where('status', '1')
      ->groupBy('psl.batchNo')
      ->get();
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();
    $data['PlantMaster'] = DB::table('master_type_dtls')
      ->select('master_type_dtls.*')
      ->where('master_type_dtls.parent_id', 42)
      ->get();
    $data['materialMaster'] = DB::table('tbl_factory_material_master_laravel')
      ->select('id', 'title')
      ->get();
    $data['DmgRsn'] = DB::table('master_type_dtls')
      ->select('master_type_dtls.*')
      ->where('master_type_dtls.parent_id', 44)
      ->get();
    $data['DmgCat'] = DB::table('master_type_dtls')
      ->select('master_type_dtls.*')
      ->where('master_type_dtls.parent_id', 43)
      ->get();
    if (isset($_GET['batchno'])) {
      $batchNo = $_GET['batchno'];

      $batchData = DB::table('tbl_factory_production_setup_laravel as psl')
        ->select(
          'psl.wattage',
          'psml.material AS mid',
          'psml.size AS msize',
          'psml.qty AS mqty',
          'psml.brand AS mbrand',
          'mml.title as mname',
          'prj_material.id AS pid',
          'prj_material.material_name as matname'
        )
        ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
        ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'psml.material')
        ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
        ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
        ->orderBy('psl.created_at', 'DESC')
        ->where('psl.batchNo', $batchNo)
        ->get();
      foreach ($batchData as $batch)

        $materials[] = [
          'mid' => $batch->mid ?? 'N/A',
          'mname' => $batch->mname ?? 'N/A',
          'msize' => $batch->msize ?? 'N/A',
          'mqty' => $batch->mqty ?? 'N/A',
          'mbrand' => $batch->mbrand ?? 'N/A',
        ];

      $data['pid'] = $batch->pid ?? 'N/A';
      $data['batchno'] = $materials;
      $data['wattage'] = $batch->wattage ?? 'N/A';
      $data['matname'] = $batch->matname ?? 'N/A';
    } else {
      $data['batchno'] = [];
    }

    $MAT_DATA = DB::table('bom')
      ->select(
        'bom_material.id as Raw_Material_ID',
        'bom_material.Material as Raw_Material',
        'prj_material.material_name as Raw_Material_Name'
      )
      ->leftJoin('bom_material', 'bom_material.BOM_ID', '=', 'bom.id')
      ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'bom_material.Material')
      ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
      ->where('materialmanagement_add_material.Approve_status', 'APPROVE')
      ->where('materialmanagement_add_material.Approve_Step', 3)
      ->where('bom.Approve_status', 'APPROVE')
      ->where('bom.Approve_Step', 3)
      ->where('bom.Raw_Material_FG', '=', Session::get('bushing_finishGood'))
      ->orderBy('prj_material.material_name', 'ASC')
      ->groupBy(
        'bom_material.id',
        'bom_material.Material'
      )
      ->get();
    $data['FinishedGood'] = $MAT_DATA;
    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->get();
      
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.add-bushing-setup', $data);
  }

  public function getFinishedGoodData(Request $request)
  {
    $finishedGoodHidden = request()->session()->get('bushing_finishGood');
    $MAT_DATA = DB::table('bom')
      ->select(
        'bom_material.id as Raw_Material_ID',
        'bom_material.Material as Raw_Material',
        'prj_material.material_name as Raw_Material_Name'
      )
      ->leftJoin('bom_material', 'bom_material.BOM_ID', '=', 'bom.id')
      ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'bom_material.Material')
      ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
      ->where('materialmanagement_add_material.Approve_status', 'APPROVE')
      ->where('materialmanagement_add_material.Approve_Step', 3)
      ->where('bom.Approve_status', 'APPROVE')
      ->where('bom.Approve_Step', 3)
      ->where('bom.Raw_Material_FG', '=', $finishedGoodHidden)
      ->orderBy('prj_material.material_name', 'ASC')
      ->groupBy(
        'bom_material.id',
        'bom_material.Material'
      )
      ->get();

    $DmgRsn = DB::table('master_type_dtls')
      ->where('parent_id', 44)
      ->get();

    $DmgCat = DB::table('master_type_dtls')
      ->where('parent_id', 44)
      ->get();

    return response()->json([
      'FinishedGood' => $MAT_DATA,
      'DmgRsn' => $DmgRsn,
      'DmgCat' => $DmgCat,
    ]);
  }

  public function view_bushing_setup($id = null)
  {
    $data['menu'] = 'bushing-setup';
    $data['bushingData'] = DB::table('tbl_factory_bushing_laravel as bol')
      ->select(
        'bol.*',
        'psl.wattage',
        'sh.shift AS shiftdtl',
        'a.fullname AS bushing_operator',
        'b.fullname AS bushing_incherge',
        'c.fullname AS createdBy',
        'prj_material.material_name as matname'
      )
      ->leftJoin('tbl_factory_production_setup_laravel AS psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
      ->leftJoin('tbl_factory_production_setup_material_laravel AS psml', 'psml.batchNo', '=', 'psl.batchNo')
      ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
      ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
      ->leftJoin('tbl_factory_bushing_material_laravel AS bml', 'bml.bushingId', '=', 'bol.bushing_id')
      ->leftJoin('hr_mstr_shift AS sh', 'sh.id', '=', 'bol.bushing_shift')
      ->leftJoin('mstr_emp AS a', 'bol.bushing_operator', '=', 'a.id')
      ->leftJoin('mstr_emp AS b', 'bol.bushing_incherge', '=', 'b.id')
      ->leftJoin('mstr_emp AS c', 'bol.created_by', '=', 'c.id')
      ->where('bol.bushing_id', $id)
      ->first();
    $data['bushingMat'] = DB::table('tbl_factory_bushing_material_laravel as bml')
      ->select(
        'bml.status',
        'mml.title AS mname',
        'psml.size',
        'psml.qty',
        'psml.brand'
      )
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'bml.prd_matId')
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.material', '=', 'bml.prd_matId')
      ->where('psml.batchNo', $data['bushingData']->bushing_batchNo)
      ->where('bml.bushingId', $id)
      ->get();
    $data['bushingDamageMat'] = DB::table('tbl_factory_bushing_damage_material_laravel as bdml')
      ->select(
        'bdml.*',
        'prj_material.material_name AS mname'
      )
      ->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'bdml.finishedGoodId')
      ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
      ->where('bdml.bushId', $id)
      ->get();
    //  dd($data['bushingDamageMat']); 
    
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.view-bushing-setup', $data);
  }

  public function bushing_details()
  {
    $data['menu'] = 'bushing-details';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $Cond = [];
    $Condition = 'psml.material = 1';

    if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
      $Cond[] = "bol.created_by = '" . $_GET['createdBy'] . "'";
    }
    if (isset($_GET['operator']) && $_GET['operator'] != '') {
      $Cond[] = "bol.bushing_operator = '" . $_GET['operator'] . "'";
    }
    if (isset($_GET['checker']) && $_GET['checker'] != '') {
      $Cond[] = "bol.bushing_incherge = '" . $_GET['checker'] . "'";
    }
    if (isset($_GET['shift']) && $_GET['shift'] != '') {
      $Cond[] = "bol.bushing_shift = '" . $_GET['shift'] . "'";
    }
    if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
    }
    if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
    }
    if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $_GET['batchNo'] . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }

    $sql = "SELECT 
        bol.*,
        psl.wattage,
        psml.size AS cellSize,
        psml.brand,
        sh.shift AS shiftdtl,
        a.fullname AS bushing_operator,
        b.fullname AS bushing_incherge,
        c.fullname AS createdBy
    FROM tbl_factory_bushing_laravel AS bol
    LEFT JOIN hr_mstr_shift AS sh 
        ON sh.id = bol.bushing_shift
    LEFT JOIN tbl_factory_production_setup_laravel AS psl 
        ON psl.batchNo = bol.bushing_batchNo
    LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
        ON psml.batchNo = psl.batchNo
    LEFT JOIN mstr_emp AS a 
        ON bol.bushing_operator = a.id
    LEFT JOIN mstr_emp AS b 
        ON bol.bushing_incherge = b.id
    LEFT JOIN mstr_emp AS c 
        ON bol.created_by = c.id
    WHERE $Condition
    ORDER BY bol.created_at DESC";

    $data['AllLists'] = DB::select($sql);
    $data['Allmats'] = DB::table('tbl_factory_material_master_laravel as mml')
      ->select(
        'mml.id',
        'mml.title as mname'
      )
      ->get();
    $AllMatLists = DB::table('tbl_factory_bushing_material_laravel as bml')
      ->select(
        'bml.bushingId',
        'mml.id as matId',
        'mml.title as mname',
        'psml.size',
        'psml.brand',
        'psml.qty',
        'bol.bushing_batchNo'
      )
      ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'bml.bushingId') // link to batch
      ->join('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'bml.prd_matId') // material master
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', function ($join) {
        $join->on('psml.material', '=', 'bml.prd_matId')
          ->on('psml.batchNo', '=', 'bol.bushing_batchNo'); // match batchNo from bushing
      })
      ->get()
      ->groupBy('bushing_batchNo'); // group by batchNo for easy lookup
    $data['AllMatLists'] = $AllMatLists;


    // dd($data['AllMatLists']);
    
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-details', $data);
  }

//   public function bushing_damage_report()
//   {
//     $data['menu'] = 'bushing-damage-report';
//     $data['ShiftMaster'] = DB::table('hr_mstr_shift')
//       ->select('hr_mstr_shift.*')
//       ->get();

//     $data['userList'] = DB::table('mstr_emp')
//       ->select('mstr_emp.id', 'mstr_emp.fullname')
//       ->get();

//     $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
//       ->select('tbl_factory_bushing_laravel.bushing_batchNo')
//       ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
//       ->get();

//     $Cond = [];
//     $Condition = '1=1';

//     if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
//       $Cond[] = "bol.created_by = '" . $_GET['createdBy'] . "'";
//     }
//     if (isset($_GET['operator']) && $_GET['operator'] != '') {
//       $Cond[] = "bol.bushing_operator = '" . $_GET['operator'] . "'";
//     }
//     if (isset($_GET['checker']) && $_GET['checker'] != '') {
//       $Cond[] = "bol.bushing_incherge = '" . $_GET['checker'] . "'";
//     }
//     if (isset($_GET['shift']) && $_GET['shift'] != '') {
//       $Cond[] = "bol.bushing_shift = '" . $_GET['shift'] . "'";
//     }
//     if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
//       $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
//     }
//     if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
//       $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
//     }
//     if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
//       $Cond[] = "bol.bushing_batchNo = '" . $_GET['batchNo'] . "'";
//     }
//     if (count($Cond) > 0) {
//       $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
//     }

//     $sql = "SELECT 
//     bol.bushing_id,
//     bol.bushing_date,
//     bol.bushing_time,
//     bol.bushing_batchNo,
//     bol.bushing_rfid,
//     bol.bushing_barCode,
//     bdml.*,
//     psl.wattage,
//     sh.shift AS shiftdtl,
//     a.fullname AS bushing_operator,
//     b.fullname AS bushing_incherge,
//     c.fullname AS createdBy,
//     prj_material.material_name as matname
//     FROM tbl_factory_bushing_laravel AS bol
//     JOIN tbl_factory_bushing_damage_material_laravel AS bdml 
//         ON bdml.bushId = bol.bushing_id
//     LEFT JOIN hr_mstr_shift AS sh 
//         ON sh.id = bol.bushing_shift
//     LEFT JOIN tbl_factory_production_setup_laravel AS psl 
//         ON psl.batchNo = bol.bushing_batchNo
//     LEFT JOIN mstr_emp AS a 
//         ON bol.bushing_operator = a.id
//     LEFT JOIN mstr_emp AS b 
//         ON bol.bushing_incherge = b.id
//     LEFT JOIN mstr_emp AS c 
//         ON bol.created_by = c.id
//     LEFT JOIN materialmanagement_add_material 
//         ON materialmanagement_add_material.id = bdml.finishedGoodId
//     LEFT JOIN prj_material 
//         ON materialmanagement_add_material.Material_Name = prj_material.id
//     WHERE $Condition
//     ORDER BY bol.created_at DESC";
//     $data['AllLists'] = DB::select($sql);
//     // dd($data['AllLists']);

//     $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
//     return view('ProductionLineUp.BushingSetup.bushing-damage-report', $data);
//   }
  
  public function bushing_damage_report()
  {
    $data['menu'] = 'bushing-damage-report';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $Cond = [];
    $Condition = "1=1";

    if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
      $Cond[] = "bol.created_by = '" . $_GET['createdBy'] . "'";
    }
    if (isset($_GET['operator']) && $_GET['operator'] != '') {
      $Cond[] = "bol.bushing_operator = '" . $_GET['operator'] . "'";
    }
    if (isset($_GET['checker']) && $_GET['checker'] != '') {
      $Cond[] = "bol.bushing_incherge = '" . $_GET['checker'] . "'";
    }
    if (isset($_GET['shift']) && $_GET['shift'] != '') {
      $Cond[] = "bol.bushing_shift = '" . $_GET['shift'] . "'";
    }
    if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
    }
    if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
    }
    if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $_GET['batchNo'] . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }
    $sql = "SELECT 
    bol.bushing_id,
    bol.bushing_date,
    bol.bushing_time,
    bol.bushing_batchNo,
    bol.bushing_rfid,
    bol.bushing_barCode,
    bdml.*,
    bm.Basic_Amount_unit,
    (bm.Basic_Amount_unit * bdml.dmgQty) AS amount,
    psl.wattage,
    sh.shift AS shiftdtl,
    a.fullname AS bushing_operator,
    b.fullname AS bushing_incherge,
    c.fullname AS createdBy,
    prj_material.material_name as matname
    FROM tbl_factory_bushing_laravel AS bol
    JOIN tbl_factory_bushing_damage_material_laravel AS bdml 
        ON bdml.bushId = bol.bushing_id
    LEFT JOIN hr_mstr_shift AS sh 
        ON sh.id = bol.bushing_shift
    LEFT JOIN tbl_factory_production_setup_laravel AS psl 
        ON psl.batchNo = bol.bushing_batchNo
    LEFT JOIN mstr_emp AS a 
        ON bol.bushing_operator = a.id
    LEFT JOIN mstr_emp AS b 
        ON bol.bushing_incherge = b.id
    LEFT JOIN mstr_emp AS c 
        ON bol.created_by = c.id
    /* CHANGE THESE TO LEFT JOIN */
    LEFT JOIN bom_material AS bm 
        ON bm.Material = bdml.finishedGoodId
    LEFT JOIN bom AS bom 
        ON bom.id = bm.BOM_ID
    LEFT JOIN materialmanagement_add_material
        ON materialmanagement_add_material.id = bdml.finishedGoodId
    LEFT JOIN prj_material 
        ON materialmanagement_add_material.Material_Name = prj_material.id
    WHERE $Condition
      AND (bom.id IS NULL OR bom.id IN (
          SELECT MAX(id) 
          FROM bom 
          WHERE Approve_status = 'APPROVE' AND Approve_Step = 3 
          GROUP BY Raw_Material_FG
      ))
    ORDER BY bol.created_at DESC";
    // dd($sql);
    $data['AllLists'] = DB::select($sql);
    // dd($data['AllLists']);
    
    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-damage-report', $data);
  }

  public function insert()
  {
    $id = date('YmdHis');
    $data = array(
      'bushing_id' => $id,
      'bushing_date' => date('d-m-Y'),
      'bushing_time' => date('H:i:s'),
      'bushing_operator' => request()->input('operator'),
      'bushing_batchNo' => request()->input('batchNo'),
      'bushing_incherge' => request()->input('incherge'),
      'bushing_shift' => request()->input('shift'),
      'bushing_plant' => request()->input('plant'),
      'bushing_logo' => request()->input('logo'),
      'bushing_hasDamage' => request()->input('hasDamage'),
      'bushing_rfid' => request()->input('rfid'),
      'bushing_barCode' => request()->input('barCode'),
      'created_by' => request()->session()->get('empId')
    );

    $res = Bushing_Model::create($data);
    $material = request()->input('mat');
    $mat_stat = request()->input('mat_stat');
    foreach ($material as $key => $value) {
      $data = array(
        'bushingId' => $id,
        'prd_matId' => $material[$key],
        'status' => $mat_stat[$key]
      );

      BushingMaterial_Model::create($data);
    }

    if (request()->input('hasDamage') == 'Yes') {
      $dmgMaterial = request()->input('dmgMat');
      $dmgMat_qty = request()->input('dmgMat_qty');
      $dmgMat_uom = request()->input('dmgMat_uom');
      $dmgMat_reason = request()->input('dmgMat_reason');
      $dmgMat_cat = request()->input('dmgMat_cat');
      foreach ($dmgMaterial as $key => $value) {
        $data = array(
          'bushId' => $id,
          'finishedGoodId' => $dmgMaterial[$key],
          'dmgQty' => $dmgMat_qty[$key],
          'dmgUOM' => $dmgMat_uom[$key],
          'dmgReason' => $dmgMat_reason[$key],
          'dmgCategory' => $dmgMat_cat[$key],
        );

        BushingDamageMaterial_Model::create($data);
      }
    }
    $lock = request()->input('lock');
    $batchNo = request()->input('batchNo');
    $oprtr = request()->input('operator');
    $batchNo = request()->input('batchNo');
    $incherge = request()->input('incherge');
    $shift = request()->input('shift');
    $plant = request()->input('plant');
    if ($res->exists) {
      if ($lock) {
        $url = 'production-lineup/bushing-setup/add?lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&batchno=' . $batchNo . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
        return redirect()->to(url($url))->with('success', 'Schedule added Successfully');
      } else {
        return redirect()->to(url('production-lineup/bushing-setup/add'))->with('success', 'Bushing request added Successfully');
      }
    } else {
      return redirect()->to(url('production-lineup/bushing-setup/add'))->with('failed', 'Bushing request Failed');
    }
  }

  public function validateRFID(Request $request)
  {
    $rfid = $request->get('rfid');
    $exists = DB::table('tbl_factory_bushing_laravel')
      ->where('bushing_rfid', $rfid)
      ->exists();

    return response()->json(['exists' => $exists]);
  }

  public function validateBarCode(Request $request)
  {
      $barCode = $request->get('barCode');

      // Condition 1: Must exist in tbl_demo_barcode
      $validBarcode = DB::table('factory_serial_number_details')
          ->leftJoin('factory_serial_numbers as sl', 'factory_serial_number_details.sl_id', '=', 'sl.id')
          ->where('sl.Approve_status','APPROVE')
          ->where('factory_serial_number_details.sl_no', $barCode)
          ->exists();

      // Condition 2: Must not already exist in tbl_factory_bushing_laravel
      $alreadyUsed = DB::table('tbl_factory_bushing_laravel')
          ->where('bushing_barCode', $barCode)
          ->exists();

      if (!$validBarcode) {
          return response()->json([
              'status' => 'error',
              'message' => 'Barcode is not valid.',
          ]);
      }

      if ($alreadyUsed) {
          return response()->json([
              'status' => 'error',
              'message' => 'This Bar Code is already in use. Please scan a different Bar Code.',
          ]);
      }

      // ✅ If it passes both conditions
      return response()->json([
          'status' => 'success',
          'message' => 'Barcode is valid and available.',
      ]);
  }
  
  public function getUOM(Request $request)
  {
    $matId = $request->get('matId');
    $uom = DB::table('bom_material')
      ->select('bom_material.UOM') 
      ->where('bom_material.Material', $matId)
      ->first();

    return response()->json(['uom' => $uom->UOM ?? 'N/A']);
  }
  
  
  public function exportBushMaterial(Request $request)
  {
    $Cond = [];
    $Condition = '1=1';

    if ($request->has('createdBy') && $request->input('createdBy') != '') {
      $Cond[] = "bol.created_by = '" . $request->input('createdBy') . "'";
    }
    if ($request->has('operator') && $request->input('operator') != '') {
      $Cond[] = "bol.bushing_operator = '" . $request->input('operator') . "'";
    }
    if ($request->has('checker') && $request->input('checker') != '') {
      $Cond[] = "bol.bushing_incherge = '" . $request->input('checker') . "'";
    }
    if ($request->has('shift') && $request->input('shift') != '') {
      $Cond[] = "bol.bushing_shift = '" . $request->input('shift') . "'";
    }
    if ($request->has('fromDate') && $request->input('fromDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $request->input('fromDate') . "'";
    }
    if ($request->has('toDate') && $request->input('toDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $request->input('toDate') . "'";
    }
    if ($request->has('batchNo') && $request->input('batchNo') != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $request->input('batchNo') . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }

    $sql = "SELECT 
          bol.*,
          psl.wattage,
          sh.shift AS shiftdtl,
          a.fullname AS bushing_operator,
          b.fullname AS bushing_incherge,
          c.fullname AS createdBy
      FROM tbl_factory_bushing_laravel AS bol
      LEFT JOIN hr_mstr_shift AS sh 
          ON sh.id = bol.bushing_shift
      LEFT JOIN tbl_factory_production_setup_laravel AS psl 
          ON psl.batchNo = bol.bushing_batchNo
      LEFT JOIN mstr_emp AS a 
          ON bol.bushing_operator = a.id
      LEFT JOIN mstr_emp AS b 
          ON bol.bushing_incherge = b.id
      LEFT JOIN mstr_emp AS c 
          ON bol.created_by = c.id
      WHERE $Condition
      ORDER BY bol.created_at DESC";

    $AllLists = DB::select($sql);

    if (empty($AllLists)) {
      return response()->json(['error' => 'No data available.']);
    }

    $batchNos = array_map(function ($item) {
      return $item->bushing_batchNo;
    }, $AllLists);

    $materialsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.qty', 'psml.size', 'psml.brand', 'mml.title as mname', 'mml.id as matId')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
      ->whereIn('psml.batchNo', $batchNos)
      ->get()
      ->groupBy('batchNo');

    $cellDetailsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.size as cellSize', 'psml.brand')
      ->whereIn('psml.batchNo', $batchNos)
      ->groupBy('psml.batchNo', 'psml.size', 'psml.brand')
      ->get()
      ->keyBy('batchNo');

    $materialNames = DB::table('tbl_factory_material_master_laravel as mml')
      ->select('mml.id', 'mml.title as mname')
      ->get();

    $filename = "bushing_details_" . date('YmdHis') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $output = fopen("php://output", "w");

    $mainHeader = [
      "",
      "",
      "",
      "",
      "Bushing",
      "",
      "",
      "",
      "",
      "",
      "Module Watt",
      "Cell Efficiency & Brand",
      "",
    ];

    foreach ($materialNames as $material) {
      $mainHeader[] = "";
      $mainHeader[] = $material->mname;
      $mainHeader[] = "";
    }

    $mainHeader[] = "";

    fputcsv($output, $mainHeader, "\t");

    $subHeader = [
      "SL No",
      "Batch No",
      "Date",
      "Time",
      "Shift",
      "Bar Code",
      "RFID",
      "Created By",
      "Operator",
      "Incharge",
      "Watt",
      "Brand",
      "Efficiency"
    ];

    foreach ($materialNames as $material) {
      $subHeader[] = "Qty";
      $subHeader[] = "Size";
      $subHeader[] = "Brand";
    }

    $subHeader[] = "Logo";

    fputcsv($output, $subHeader, "\t");

    foreach ($AllLists as $key => $item) {
      $cellDetail = $cellDetailsByBatch[$item->bushing_batchNo] ?? null;

      $row = [
        $key + 1,
        $item->bushing_batchNo,
        $item->bushing_date,
        \Carbon\Carbon::parse($item->bushing_time)->format('h:i A'),
        $item->shiftdtl,
        $item->bushing_barCode,
        $item->bushing_rfid,
        $item->createdBy,
        $item->bushing_operator,
        $item->bushing_incherge,
        $item->wattage,
        $cellDetail->brand ?? '-',
        $cellDetail->cellSize ?? '-'
      ];

      $materials = $materialsByBatch[$item->bushing_batchNo] ?? [];
      foreach ($materialNames as $material) {
        $materialData = collect($materials)->firstWhere('matId', $material->id);
        $row[] = $materialData->qty ?? '-';
        $row[] = $materialData->size ?? '-';
        $row[] = $materialData->brand ?? '-';
      }

      $row[] = $item->bushing_logo ?? '-';

      fputcsv($output, $row, "\t");
    }

    fclose($output);
    exit;
  }

  public function exportBushMaterial2(Request $request)
  {
    $Cond = [];
    $Condition = '1=1';

    if ($request->has('createdBy') && $request->input('createdBy') != '') {
      $Cond[] = "bol.created_by = '" . $request->input('createdBy') . "'";
    }
    if ($request->has('operator') && $request->input('operator') != '') {
      $Cond[] = "bol.bushing_operator = '" . $request->input('operator') . "'";
    }
    if ($request->has('checker') && $request->input('checker') != '') {
      $Cond[] = "bol.bushing_incherge = '" . $request->input('checker') . "'";
    }
    if ($request->has('shift') && $request->input('shift') != '') {
      $Cond[] = "bol.bushing_shift = '" . $request->input('shift') . "'";
    }
    if ($request->has('fromDate') && $request->input('fromDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $request->input('fromDate') . "'";
    }
    if ($request->has('toDate') && $request->input('toDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $request->input('toDate') . "'";
    }
    if ($request->has('batchNo') && $request->input('batchNo') != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $request->input('batchNo') . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }

    $sql = "SELECT 
          bol.*,
          psl.wattage,
          sh.shift AS shiftdtl,
          a.fullname AS bushing_operator,
          b.fullname AS bushing_incherge,
          c.fullname AS createdBy
      FROM tbl_factory_bushing_laravel AS bol
      LEFT JOIN hr_mstr_shift AS sh 
          ON sh.id = bol.bushing_shift
      LEFT JOIN tbl_factory_production_setup_laravel AS psl 
          ON psl.batchNo = bol.bushing_batchNo
      LEFT JOIN mstr_emp AS a 
          ON bol.bushing_operator = a.id
      LEFT JOIN mstr_emp AS b 
          ON bol.bushing_incherge = b.id
      LEFT JOIN mstr_emp AS c 
          ON bol.created_by = c.id
      WHERE $Condition
      ORDER BY bol.created_at DESC";

    $AllLists = DB::select($sql);

    if (empty($AllLists)) {
      return response()->json(['error' => 'No data available for export.']);
    }

    $batchNos = array_map(function ($item) {
      return $item->bushing_batchNo;
    }, $AllLists);

    $materialsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.qty', 'psml.size', 'psml.brand', 'mml.title as mname', 'mml.id as matId')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
      ->whereIn('psml.batchNo', $batchNos)
      ->get()
      ->groupBy('batchNo');

    $cellDetailsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.size as cellSize', 'psml.brand')
      ->whereIn('psml.batchNo', $batchNos)
      ->groupBy('psml.batchNo', 'psml.size', 'psml.brand')
      ->get()
      ->keyBy('batchNo');

    $materialNames = DB::table('tbl_factory_material_master_laravel as mml')
      ->select('mml.id', 'mml.title as mname')
      ->get();

    $filename = "bushing_details_" . date('YmdHis') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    echo '<html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <style>
              table { border-collapse: collapse; width: 100%; font-family: Arial; }
              th { border: 1px solid black; padding: 8px; background-color: #f2f2f2; font-weight: bold; text-align: center; }
              td { border: 1px solid black; padding: 6px; }
              .text-center { text-align: center; }
              .text-left { text-align: left; }
          </style>
      </head>
      <body>
          <table>';

    echo '<tr>';
    echo '<th style="background-color: #d9d9d9;" colspan="13">Bushing Information</th>';
    echo '<th style="background-color: #d9d9d9;" colspan="' . (count($materialNames) * 3) . '">Material Details</th>';
    echo '<th style="background-color: #d9d9d9;" rowspan="3">Logo</th>';
    echo '</tr>';

    echo '<tr>';

    $bushingHeaders = [
      'SL No',
      'Batch No',
      'Date',
      'Time',
      'Shift',
      'Bar Code',
      'RFID',
      'Created By',
      'Operator',
      'Incharge',
      'Module Watt',
      'Brand',
      'Efficiency'
    ];

    foreach ($bushingHeaders as $header) {
      echo '<th>' . $header . '</th>';
    }

    foreach ($materialNames as $material) {
      echo '<th colspan="3" style="background-color: #e6f7ff;">' . $material->mname . '</th>';
    }

    echo '</tr>';
    echo '<tr>';

    for ($i = 0; $i < 13; $i++) {
      echo '<th></th>';
    }

    foreach ($materialNames as $material) {
      echo '<th style="background-color: #e6f7ff;">Qty</th>';
      echo '<th style="background-color: #e6f7ff;">Size</th>';
      echo '<th style="background-color: #e6f7ff;">Brand</th>';
    }

    echo '</tr>';

    foreach ($AllLists as $key => $item) {
      $cellDetail = $cellDetailsByBatch[$item->bushing_batchNo] ?? null;

      echo '<tr>';
      // Bushing data
      echo '<td class="text-center">' . ($key + 1) . '</td>';
      echo '<td class="text-left">' . $item->bushing_batchNo . '</td>';
      echo '<td class="text-center">' . $item->bushing_date . '</td>';
      echo '<td class="text-center">' . \Carbon\Carbon::parse($item->bushing_time)->format('h:i A') . '</td>';
      echo '<td class="text-center">' . $item->shiftdtl . '</td>';
      echo '<td class="text-left">' . $item->bushing_barCode . '</td>';
      echo '<td class="text-left">' . $item->bushing_rfid . '</td>';
      echo '<td class="text-left">' . $item->createdBy . '</td>';
      echo '<td class="text-left">' . $item->bushing_operator . '</td>';
      echo '<td class="text-left">' . $item->bushing_incherge . '</td>';
      echo '<td class="text-center">' . $item->wattage . '</td>';
      echo '<td class="text-left">' . ($cellDetail->brand ?? '-') . '</td>';
      echo '<td class="text-center">' . ($cellDetail->cellSize ?? '-') . '</td>';

      // Material data
      $materials = $materialsByBatch[$item->bushing_batchNo] ?? [];
      foreach ($materialNames as $material) {
        $materialData = collect($materials)->firstWhere('matId', $material->id);
        echo '<td class="text-center">' . ($materialData->qty ?? '-') . '</td>';
        echo '<td class="text-center">' . ($materialData->size ?? '-') . '</td>';
        echo '<td class="text-left">' . ($materialData->brand ?? '-') . '</td>';
      }

      echo '<td class="text-left">' . ($item->bushing_logo ?? '-') . '</td>';
      echo '</tr>';
    }

    echo '</table>
      </body>
      </html>';

    exit;
  }

  public function pdfBushMaterial(Request $request)
  {
    $Cond = [];
    $Condition = '1=1';

    // Apply the same filters as your export function
    if ($request->has('createdBy') && $request->input('createdBy') != '') {
      $Cond[] = "bol.created_by = '" . $request->input('createdBy') . "'";
    }
    if ($request->has('operator') && $request->input('operator') != '') {
      $Cond[] = "bol.bushing_operator = '" . $request->input('operator') . "'";
    }
    if ($request->has('checker') && $request->input('checker') != '') {
      $Cond[] = "bol.bushing_incherge = '" . $request->input('checker') . "'";
    }
    if ($request->has('shift') && $request->input('shift') != '') {
      $Cond[] = "bol.bushing_shift = '" . $request->input('shift') . "'";
    }
    if ($request->has('fromDate') && $request->input('fromDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) >= '" . $request->input('fromDate') . "'";
    }
    if ($request->has('toDate') && $request->input('toDate') != '') {
      $Cond[] = "CAST(bol.created_at AS DATE) <= '" . $request->input('toDate') . "'";
    }
    if ($request->has('batchNo') && $request->input('batchNo') != '') {
      $Cond[] = "bol.bushing_batchNo = '" . $request->input('batchNo') . "'";
    }
    if (count($Cond) > 0) {
      $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
    }

    $sql = "SELECT 
        bol.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS bushing_operator,
        b.fullname AS bushing_incherge,
        c.fullname AS createdBy
    FROM tbl_factory_bushing_laravel AS bol
    LEFT JOIN hr_mstr_shift AS sh 
        ON sh.id = bol.bushing_shift
    LEFT JOIN tbl_factory_production_setup_laravel AS psl 
        ON psl.batchNo = bol.bushing_batchNo
    LEFT JOIN mstr_emp AS a 
        ON bol.bushing_operator = a.id
    LEFT JOIN mstr_emp AS b 
        ON bol.bushing_incherge = b.id
    LEFT JOIN mstr_emp AS c 
        ON bol.created_by = c.id
    WHERE $Condition
    ORDER BY bol.created_at DESC";

    $AllLists = DB::select($sql);

    if (empty($AllLists)) {
      return response()->json(['error' => 'No data available for PDF generation.']);
    }

    $batchNos = array_map(function ($item) {
      return $item->bushing_batchNo;
    }, $AllLists);

    // Get all materials used in the filtered batches
    $materialsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.qty', 'psml.size', 'psml.brand', 'mml.title as mname', 'mml.id as matId')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
      ->whereIn('psml.batchNo', $batchNos)
      ->get()
      ->groupBy('batchNo');

    $cellDetailsByBatch = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('psml.batchNo', 'psml.size as cellSize', 'psml.brand')
      ->whereIn('psml.batchNo', $batchNos)
      ->groupBy('psml.batchNo', 'psml.size', 'psml.brand')
      ->get()
      ->keyBy('batchNo');

    // Get unique material names from the actual data (not from master)
    $materialNames = DB::table('tbl_factory_production_setup_material_laravel as psml')
      ->select('mml.id', 'mml.title as mname')
      ->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
      ->whereIn('psml.batchNo', $batchNos)
      ->groupBy('mml.id', 'mml.title')
      ->get();

    // If no materials found in the data, get from master as fallback
    if ($materialNames->isEmpty()) {
      $materialNames = DB::table('tbl_factory_material_master_laravel as mml')
        ->select('mml.id', 'mml.title as mname')
        ->get();
    }

    // Debug: Check what materials we have
    // dd($materialNames);

    // Generate HTML content for PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Layout Material Report</title>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                font-size: 10px;
                margin: 10px;
            }
            .header { 
                text-align: center; 
                margin-bottom: 15px;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }
            .header h1 { 
                color: #333; 
                margin: 0;
                font-size: 16px;
            }
            .header p { 
                margin: 5px 0; 
                font-size: 10px;
            }
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 10px;
            }
            th, td { 
                border: 1px solid #333; 
                padding: 4px 6px; 
                text-align: left;
                font-size: 8px;
            }
            th { 
                background-color: #f2f2f2; 
                font-weight: bold;
                text-align: center;
            }
            .material-header {
                background-color: #e6e6e6;
                text-align: center;
                font-weight: bold;
            }
            .sub-header th {
                background-color: #d9d9d9;
            }
            .text-center { text-align: center; }
            .page-break {
                page-break-after: always;
            }
            .filters {
                margin-bottom: 10px;
                padding: 8px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                font-size: 9px;
            }
            .filters strong {
                color: #495057;
            }
            .material-column {
                min-width: 60px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>LAYOUT MATERIAL REPORT</h1>
            <p style="text-align:right;">Generated on: ' . date('Y-m-d H:i:s') . '</p>
        </div>';

    // Display applied filters
    $html .= '<div class="filters">';
    $filters = [];
    if ($request->has('fromDate') && $request->input('fromDate') != '') {
      $filters[] = 'From: ' . $request->input('fromDate');
    }
    if ($request->has('toDate') && $request->input('toDate') != '') {
      $filters[] = 'To: ' . $request->input('toDate');
    }
    if ($request->has('batchNo') && $request->input('batchNo') != '') {
      $filters[] = 'Batch: ' . $request->input('batchNo');
    }
    if ($request->has('shift') && $request->input('shift') != '') {
      $filters[] = 'Shift: ' . $request->input('shift');
    }
    $html .= empty($filters) ? 'Showing All Records' : implode(' | ', $filters);
    $html .= '</div>';

    $html .= '
        <table>
            <thead>
                <tr class="material-header">
                    <th colspan="13" style="text-align: center;">LAYOUT DETAILS</th>
                    <th colspan="' . (count($materialNames) * 3) . '" style="text-align: center;">MATERIALS</th>
                    <th rowspan="3">Logo</th>
                </tr>
                <tr class="sub-header">';

    // Fixed bushing detail headers (13 columns) with rowspan="2"
    $bushingHeaders = [
      'SL No',
      'Batch No',
      'Date',
      'Time',
      'Shift',
      'Bar Code',
      'RFID',
      'Created By',
      'Operator',
      'Incharge',
      'Watt',
      'Brand',
      'Efficiency'
    ];

    foreach ($bushingHeaders as $header) {
      $html .= '<th rowspan="2">' . $header . '</th>';
    }

    // Material name headers
    foreach ($materialNames as $material) {
      $html .= '<th colspan="3">' . ($material->mname ?? 'Material') . '</th>';
    }

    $html .= '
                </tr>
                <tr class="sub-header">';

    // No need for empty headers for bushing details since they have rowspan="2"
    // Just add material sub-headers (Qty, Size, Brand for each material)
    foreach ($materialNames as $material) {
      $html .= '<th>Qty</th><th>Size</th><th>Brand</th>';
    }

    $html .= '
                </tr>
            </thead>
            <tbody>';

    foreach ($AllLists as $key => $item) {
      $cellDetail = $cellDetailsByBatch[$item->bushing_batchNo] ?? null;
      $materials = $materialsByBatch[$item->bushing_batchNo] ?? [];

      $html .= '
                <tr>
                    <td class="text-center">' . ($key + 1) . '</td>
                    <td>' . ($item->bushing_batchNo ?? '-') . '</td>
                    <td>' . ($item->bushing_date ?? '-') . '</td>
                    <td>' . (\Carbon\Carbon::parse($item->bushing_time)->format('h:i A') ?? '-') . '</td>
                    <td>' . ($item->shiftdtl ?? '-') . '</td>
                    <td>' . ($item->bushing_barCode ?? '-') . '</td>
                    <td>' . ($item->bushing_rfid ?? '-') . '</td>
                    <td>' . ($item->createdBy ?? '-') . '</td>
                    <td>' . ($item->bushing_operator ?? '-') . '</td>
                    <td>' . ($item->bushing_incherge ?? '-') . '</td>
                    <td>' . ($item->wattage ?? '-') . '</td>
                    <td>' . ($cellDetail->brand ?? '-') . '</td>
                    <td>' . ($cellDetail->cellSize ?? '-') . '</td>';

      // Material data - FIXED: Match by material ID
      foreach ($materialNames as $material) {
        $materialData = null;
        foreach ($materials as $mat) {
          if (is_object($mat) && $mat->matId == $material->id) {
            $materialData = $mat;
            break;
          }
          if (is_array($mat) && $mat['matId'] == $material->id) {
            $materialData = (object)$mat;
            break;
          }
        }

        $html .= '<td>' . ($materialData->qty ?? '-') . '</td>';
        $html .= '<td>' . ($materialData->size ?? '-') . '</td>';
        $html .= '<td>' . ($materialData->brand ?? '-') . '</td>';
      }

      $html .= '<td>' . ($item->bushing_logo ?? '-') . '</td>';
      $html .= '</tr>';
    }

    $html .= '
            </tbody>
        </table>
        <div style="margin-top: 20px; font-size: 8px; text-align: center;">
            Total Records: ' . count($AllLists) . ' | Total Materials: ' . count($materialNames) . '
        </div>
    </body>
    </html>';

    // Generate PDF
    $pdf = PDF::loadHTML($html);
    $pdf->setPaper('A3', 'landscape');

    return $pdf->download('layout_material_' . date('YmdHis') . '.pdf');
  }

}
