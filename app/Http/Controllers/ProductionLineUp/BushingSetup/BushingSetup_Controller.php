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


  public  static function PermittedMenuList($sessionId)
  {
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

  public function index(Request $request)
  {
    $data['menu'] = 'bushing-setup';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->where('mstr_emp.status', '1')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $Cond = [];
    $Condition = 'psml.material = 1';

    
    $query = DB::table('tbl_factory_bushing_laravel as bol')
        ->select([
            'bol.*',
            'psl.wattage',
            'psml.size as cellSize',
            'psml.brand',
            'sh.shift as shiftdtl',
            'a.fullname as bushing_operator_name',
            'b.fullname as bushing_incherge_name',
            'c.fullname as createdBy_name'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
        ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

    // 2. Safely apply dynamic filters if they exist in the request
    if ($request->filled('createdBy')) {
        $query->where('bol.created_by', $request->input('createdBy'));
    }

    if ($request->filled('operator')) {
        $query->where('bol.bushing_operator', $request->input('operator'));
    }

    if ($request->filled('checker')) {
        $query->where('bol.bushing_incherge', $request->input('checker'));
    }

    if ($request->filled('shift')) {
        $query->where('bol.bushing_shift', $request->input('shift'));
    }

    if ($request->filled('fromDate')) {
        // Use whereRaw with placeholders (?) to prevent SQL injection on dates
        $query->whereRaw('CAST(bol.created_at AS DATE) >= ?', [$request->input('fromDate')]);
    }

    if ($request->filled('toDate')) {
        $query->whereRaw('CAST(bol.created_at AS DATE) <= ?', [$request->input('toDate')]);
    }

    if ($request->filled('batchNo')) {
        $query->where('bol.bushing_batchNo', $request->input('batchNo'));
    }

    // 3. Order the results and paginate (e.g., 15 items per page)
    // withQueryString() ensures your filters stay in the URL when clicking page numbers
    $data['AllLists'] = $query->orderBy('bol.created_at', 'desc')
                            ->groupBy('bol.bushing_barCode')
                              ->paginate(15);

    // dd($data['AllLists']);

    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-setup', $data);
  }

  public function allList(Request $request)
  {
    $data['menu'] = 'bushing-setup-all';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->where('mstr_emp.status', '1')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $Cond = [];
    $Condition = 'psml.material = 1';

    $query = DB::table('tbl_factory_bushing_laravel as bol')
        ->select([
            'bol.*',
            'psl.wattage',
            'psml.size as cellSize',
            'psml.brand',
            'sh.shift as shiftdtl',
            'a.fullname as bushing_operator_name',
            'b.fullname as bushing_incherge_name',
            'c.fullname as createdBy_name'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
        ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

    // 2. Safely apply dynamic filters if they exist in the request
    if ($request->filled('createdBy')) {
        $query->where('bol.created_by', $request->input('createdBy'));
    }

    if ($request->filled('operator')) {
        $query->where('bol.bushing_operator', $request->input('operator'));
    }

    if ($request->filled('checker')) {
        $query->where('bol.bushing_incherge', $request->input('checker'));
    }

    if ($request->filled('shift')) {
        $query->where('bol.bushing_shift', $request->input('shift'));
    }

    if ($request->filled('fromDate')) {
        // Use whereRaw with placeholders (?) to prevent SQL injection on dates
        $query->whereRaw('CAST(bol.created_at AS DATE) >= ?', [$request->input('fromDate')]);
    }

    if ($request->filled('toDate')) {
        $query->whereRaw('CAST(bol.created_at AS DATE) <= ?', [$request->input('toDate')]);
    }

    if ($request->filled('batchNo')) {
        $query->where('bol.bushing_batchNo', $request->input('batchNo'));
    }

    // 3. Order the results and paginate (e.g., 15 items per page)
    // withQueryString() ensures your filters stay in the URL when clicking page numbers
    $data['AllLists'] = $query->orderBy('bol.created_at', 'desc')
                            ->groupBy('bol.bushing_barCode')
                              ->paginate(15);

    // dd($data['AllLists']);

    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-setup-all', $data);
  }

  public function allExcelDownload(Request $request)
  {
      $data['menu'] = 'bushing-setup-all';

      // Initialize the query builder
      $query = DB::table('tbl_factory_bushing_laravel as bol')
      ->select([
          'bol.*',
          'psl.wattage',
          'psml.size as cellSize',
          'psml.brand',
          'sh.shift as shiftdtl',
          'a.fullname as bushing_operator_name',
          'b.fullname as bushing_incherge_name',
          'c.fullname as createdBy_name'
      ])
      ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
      ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
      ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
      ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
      ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

      // 2. Safely apply dynamic filters if they exist in the request
      if ($request->filled('createdBy')) {
          $query->where('bol.created_by', $request->input('createdBy'));
      }
  
      if ($request->filled('operator')) {
          $query->where('bol.bushing_operator', $request->input('operator'));
      }
  
      if ($request->filled('checker')) {
          $query->where('bol.bushing_incherge', $request->input('checker'));
      }
  
      if ($request->filled('shift')) {
          $query->where('bol.bushing_shift', $request->input('shift'));
      }
  
      if ($request->filled('fromDate')) {
          // Use whereRaw with placeholders (?) to prevent SQL injection on dates
          $query->whereRaw('CAST(bol.created_at AS DATE) >= ?', [$request->input('fromDate')]);
      }
  
      if ($request->filled('toDate')) {
          $query->whereRaw('CAST(bol.created_at AS DATE) <= ?', [$request->input('toDate')]);
      }
  
      if ($request->filled('batchNo')) {
          $query->where('bol.bushing_batchNo', $request->input('batchNo'));
      }
  
      // 3. Order the results and paginate (e.g., 15 items per page)
      // withQueryString() ensures your filters stay in the URL when clicking page numbers
      $AllLists = $query->orderBy('bol.created_at', 'desc')
                          ->groupBy('bol.bushing_barCode')
                            ->get();


      $fileName = 'Layup_report_' . date('Ymd_His') . '.csv';
      $headers = [
          "Content-type"        => "text/csv",
          "Content-Disposition" => "attachment; filename=$fileName",
          "Pragma"              => "no-cache",
          "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
          "Expires"             => "0"
      ];

      $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt', 'Cell Efficiency', 'Bus Bar', 'Operator', 'Incharge'];

      // 4. Create a callback to stream the data
      $callback = function() use($AllLists, $columns) {
          $file = fopen('php://output', 'w');
          
          // Add UTF-8 BOM for Excel to recognize special characters correctly
          fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

          // Write column headers
          fputcsv($file, $columns);

          // Write data rows
          foreach ($AllLists as $key=>$row) {
            $sl = $key+1;
              fputcsv($file, [
                  $sl,
                  \Carbon\Carbon::parse($row->bushing_date)->format('d/m/Y'),
                  \Carbon\Carbon::parse($row->bushing_time)->format('h:i A'),
                  $row->shiftdtl,
                  $row->bushing_barCode,
                  $row->elqc_source ?? 'Layout',
                  $row->wattage,
                  $row->cellSize,
                  $row->bus_bar ?? '-',
                  $row->bushing_operator_name,
                  $row->bushing_incherge_name,
              ]);
          }

          fclose($file);
      };

      // 5. Return the response as a stream
      return response()->stream($callback, 200, $headers);
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
      //   ->where('materialmanagement_add_material.Approve_Step', 3)
      ->where('bom.Approve_status', 'APPROVE')
      //   ->where('bom.Approve_Step', 3)
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
      ->where('mstr_emp.status', '1')
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
      //   ->where('materialmanagement_add_material.Approve_Step', 3)
      ->where('bom.Approve_status', 'APPROVE')
      //   ->where('bom.Approve_Step', 3)
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
      ->where('parent_id', 43)
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

  public function bushing_details_0ld1705()
  {
    $data['menu'] = 'bushing-details';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->where('mstr_emp.status', '1')
      ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
      ->select('tbl_factory_bushing_laravel.bushing_batchNo')
      ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
      ->get();

    $query = DB::table('tbl_factory_bushing_laravel as bol')
      ->select(
        'bol.*',
        'psl.wattage',
        'psml.size as cellSize',
        'psml.brand',
        'sh.shift as shiftdtl',
        'a.fullname as bushing_operator',
        'b.fullname as bushing_incherge',
        'c.fullname as createdBy'
      )
      ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
      ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
      ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
      ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
      ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
      ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id')
      ->where('psml.material', '=', 1)
      ->orderBy('bol.created_at', 'DESC');

    if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
      $query->where('bol.created_by', $_GET['createdBy']);
    }
    if (isset($_GET['operator']) && $_GET['operator'] != '') {
      $query->where('bol.bushing_operator', $_GET['operator']);
    }
    if (isset($_GET['checker']) && $_GET['checker'] != '') {
      $query->where('bol.bushing_incherge', $_GET['checker']);
    }
    if (isset($_GET['shift']) && $_GET['shift'] != '') {
      $query->where('bol.bushing_shift', $_GET['shift']);
    }

    // Set default date range to last 7 days if not provided
    if (!isset($_GET['fromDate']) || $_GET['fromDate'] == '') {
        $_GET['fromDate'] = now()->subDays(6)->format('Y-m-d');
    }
    if (!isset($_GET['toDate']) || $_GET['toDate'] == '') {
        $_GET['toDate'] = now()->format('Y-m-d');
    }

    if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
      $query->whereDate('bol.created_at', '>=', $_GET['fromDate']);
    }
    if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
      $query->whereDate('bol.created_at', '<=', $_GET['toDate']);
    }
    if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
      $query->where('bol.bushing_batchNo', $_GET['batchNo']);
    }

    $data['AllLists'] = $query->paginate(15);

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
  
  
  public function bushing_details()
  {
    $data['menu'] = 'bushing-details';
    
    // 1. Fetch dropdown/lookup data efficiently
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')->get();

    $data['userList'] = DB::table('mstr_emp')
        ->select('id', 'fullname')
        ->where('status', '1')
        ->get();

    $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
        ->select('bushing_batchNo')
        ->distinct() // Cleaner alternative to groupBy for unique listings
        ->get();

    // 2. Build the main query
    $query = DB::table('tbl_factory_bushing_laravel as bol')
        ->select(
            'bol.*',
            'psl.wattage',
            'psml.size as cellSize',
            'psml.brand',
            'sh.shift as shiftdtl',
            'a.fullname as bushing_operator',
            'b.fullname as bushing_incherge',
            'c.fullname as createdBy'
        )
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
        ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
        ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id')
        ->where('psml.material', '=', 1)
        ->orderBy('bol.created_at', 'DESC');

    // 3. Apply Request Filters (Using Laravel Request Helper)
    if (request('createdBy')) {
        $query->where('bol.created_by', request('createdBy'));
    }
    if (request('operator')) {
        $query->where('bol.bushing_operator', request('operator'));
    }
    if (request('checker')) {
        $query->where('bol.bushing_incherge', request('checker'));
    }
    if (request('shift')) {
        $query->where('bol.bushing_shift', request('shift'));
    }
    if (request('batchNo')) {
        $query->where('bol.bushing_batchNo', request('batchNo'));
    }

    // Set and apply strict default date ranges
    $fromDate = request('fromDate', now()->subDays(6)->format('Y-m-d'));
    $toDate = request('toDate', now()->format('Y-m-d'));

    $query->whereDate('bol.created_at', '>=', $fromDate);
    $query->whereDate('bol.created_at', '<=', $toDate);

    // Sync input parameters with global request for view compatibility
    $_GET['fromDate'] = $fromDate;
    $_GET['toDate'] = $toDate;

    // Paginate main list (Only 15 items processed)
    $paginatedLists = $query->paginate(15);
    $data['AllLists'] = $paginatedLists;

    $data['Allmats'] = DB::table('tbl_factory_material_master_laravel')
        ->select('id', 'title as mname')
        ->get();

    // 4. PERFORMANCE FIX: Only fetch material lists for the current page batches
    $currentPageBatchNos = $paginatedLists->pluck('bushing_batchNo')->unique()->toArray();

    if (!empty($currentPageBatchNos)) {
        $data['AllMatLists'] = DB::table('tbl_factory_bushing_material_laravel as bml')
            ->select(
                'bml.bushingId',
                'mml.id as matId',
                'mml.title as mname',
                'psml.size',
                'psml.brand',
                'psml.qty',
                'bol.bushing_batchNo'
            )
            ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'bml.bushingId')
            ->join('tbl_factory_material_master_laravel as mml', 'mml.id', '=', 'bml.prd_matId')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', function ($join) {
                $join->on('psml.material', '=', 'bml.prd_matId')
                     ->on('psml.batchNo', '=', 'bol.bushing_batchNo');
            })
            ->whereIn('bol.bushing_batchNo', $currentPageBatchNos) // Crucial filtering step
            ->get()
            ->groupBy('bushing_batchNo');
    } else {
        $data['AllMatLists'] = collect([]);
    }

    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    
    return view('ProductionLineUp.BushingSetup.bushing-details', $data);
  }

  

  public function bushing_damage_report()
  {
    $data['menu'] = 'bushing-damage-report';
    $data['ShiftMaster'] = DB::table('hr_mstr_shift')
      ->select('hr_mstr_shift.*')
      ->get();

    $data['userList'] = DB::table('mstr_emp')
      ->select('mstr_emp.id', 'mstr_emp.fullname')
      ->where('mstr_emp.status', '1')
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
          WHERE Approve_status = 'APPROVE'
          GROUP BY Raw_Material_FG
      ))
    GROUP BY bdml.id
    ORDER BY bol.created_at DESC";
    // dd($sql);
    $data['AllLists'] = DB::select($sql);
    // dd($data['AllLists']);

    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
    return view('ProductionLineUp.BushingSetup.bushing-damage-report', $data);
  }

  public function insert()
  {
    $exists = DB::table('tbl_factory_bushing_laravel')
      ->where('bushing_barCode', request()->input('barCode'))
      ->exists();

    if ($exists == false) {
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
        return redirect()->to(url('production-lineup/bushing-setup/add'))->with('success', 'Bushing request Failed');
      }
    } else {
      return redirect()->to(url('production-lineup/bushing-setup/add'))->with('success', 'Bushing request Failed. Duplicate Barcode');
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
    //   $validBarcode = DB::table('factory_serial_number_details')
    //       ->where('sl_no', $barCode)
    //       ->where('status', 'USED')
    //       ->exists();
    $validBarcode = DB::table('factory_serial_number_details')
      ->leftJoin('factory_serial_numbers as sl', 'factory_serial_number_details.sl_id', '=', 'sl.id')
      ->where('sl.Approve_status', 'APPROVE')
      ->whereNull('factory_serial_number_details.status')
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
      "Layout",
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
      ini_set('memory_limit', '4096M');
      set_time_limit(0);

      // -----------------------------------------------------------
      // 1. Build WHERE conditions
      // -----------------------------------------------------------
      $Cond      = [];
      $Condition = '1=1';

      if ($request->filled('createdBy') && $request->input('createdBy') != '') {
          $Cond[] = "bol.created_by = '" . $request->input('createdBy') . "'";
      }
      if ($request->filled('operator') && $request->input('operator') != '') {
          $Cond[] = "bol.bushing_operator = '" . $request->input('operator') . "'";
      }
      if ($request->filled('checker') && $request->input('checker') != '') {
          $Cond[] = "bol.bushing_incherge = '" . $request->input('checker') . "'";
      }
      if ($request->filled('shift') && $request->input('shift') != '') {
          $Cond[] = "bol.bushing_shift = '" . $request->input('shift') . "'";
      }

      $fromDate = $request->filled('fromDate')
          ? date('Y-m-d', strtotime($request->input('fromDate')))
          : now()->subDays(6)->format('Y-m-d');

      $toDate = $request->filled('toDate')
          ? date('Y-m-d', strtotime($request->input('toDate')))
          : now()->format('Y-m-d');

      $Cond[] = "STR_TO_DATE(bol.bushing_date, '%d-%m-%Y') >= '{$fromDate}'";
      $Cond[] = "STR_TO_DATE(bol.bushing_date, '%d-%m-%Y') <= '{$toDate}'";

      if ($request->filled('batchNo') && $request->input('batchNo') != '') {
          $Cond[] = "bol.bushing_batchNo = '" . $request->input('batchNo') . "'";
      }
      if (count($Cond) > 0) {
          $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
      }

      // -----------------------------------------------------------
      // 2. Fetch main records (query unchanged)
      // -----------------------------------------------------------
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

      $allLists = DB::select($sql);

      if (empty($allLists)) {
          return response()->json(['error' => 'No data available for PDF generation.']);
      }

      // -----------------------------------------------------------
      // 3. Fetch related material data (queries unchanged)
      // -----------------------------------------------------------
      $batchNos = array_map(function ($item) {
          return $item->bushing_batchNo;
      }, $allLists);

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

      $materialNames = DB::table('tbl_factory_production_setup_material_laravel as psml')
          ->select('mml.id', 'mml.title as mname')
          ->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
          ->whereIn('psml.batchNo', $batchNos)
          ->groupBy('mml.id', 'mml.title')
          ->get();

      if ($materialNames->isEmpty()) {
          $materialNames = DB::table('tbl_factory_material_master_laravel as mml')
              ->select('mml.id', 'mml.title as mname')
              ->get();
      }

      // -----------------------------------------------------------
      // 4. Build applied-filter labels (unchanged)
      // -----------------------------------------------------------
      $appliedFilters = [];
      if ($request->has('fromDate') && $request->input('fromDate') != '') {
          $appliedFilters[] = 'From: ' . $request->input('fromDate');
      }
      if ($request->has('toDate') && $request->input('toDate') != '') {
          $appliedFilters[] = 'To: ' . $request->input('toDate');
      }
      if ($request->has('batchNo') && $request->input('batchNo') != '') {
          $appliedFilters[] = 'Batch: ' . $request->input('batchNo');
      }
      if ($request->has('shift') && $request->input('shift') != '') {
          $appliedFilters[] = 'Shift: ' . $request->input('shift');
      }

      // -----------------------------------------------------------
      // 5. Render view → PDF → stream  (dd() removed, options added)
      // -----------------------------------------------------------
      $pdf = Pdf::loadView('ProductionLineUp.BushingSetup.pdf_bush_material', [
          'allLists'           => $allLists,
          'materialNames'      => $materialNames,
          'materialsByBatch'   => $materialsByBatch,
          'cellDetailsByBatch' => $cellDetailsByBatch,
          'appliedFilters'     => $appliedFilters,
          'generatedAt'        => now()->format('Y-m-d H:i:s'),
      ])
      ->setPaper('A1', 'landscape')
      ->setOptions([
          'isHtml5ParserEnabled'    => true,
          'isRemoteEnabled'         => false,
          'defaultFont'             => 'sans-serif',
          'dpi'                     => 96,
          'isFontSubsettingEnabled' => true,
          'enable_php'              => false,
      ]);

      return $pdf->download('layout_material_' . now()->format('YmdHis') . '.pdf');
  }
}
