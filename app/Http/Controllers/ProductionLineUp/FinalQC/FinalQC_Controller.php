<?php

namespace App\Http\Controllers\ProductionLineUp\FinalQC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{FinalQC_Model, FinalQC_Defect_Model, FinalQC_Hist_Model};
use App\Models\Production\{Production, ProductionBatch, ProductionData};

class FinalQC_Controller extends Controller
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
  public function index()
  {
      $data['menu'] = 'final-qc';

      $Condition = 'fqc.fqc_QC IS NULL AND jb.status = 1';
      $Cond = [];

      if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
          $Cond[] = "jb.created_by = '" . $_GET['createdBy'] . "'";
      }
      if (isset($_GET['operator']) && $_GET['operator'] != '') {
          $Cond[] = "jb.jb_operator = '" . $_GET['operator'] . "'";
      }
      if (isset($_GET['checker']) && $_GET['checker'] != '') {
          $Cond[] = "jb.jb_incharge = '" . $_GET['checker'] . "'";
      }
      if (isset($_GET['shift']) && $_GET['shift'] != '') {
          $Cond[] = "jb.jb_shift = '" . $_GET['shift'] . "'";
      }
      if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
          $Cond[] = "CAST(jb.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
      }
      if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
          $Cond[] = "CAST(jb.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
      }
      if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
          $Cond[] = "jb.jb_batchNo = '" . $_GET['batchNo'] . "'";
      }
      if (count($Cond) > 0) {
          $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
      }

      $sql = "SELECT 
          jb.*,
          psl.wattage,
          psml.size AS cellSize,
          sh.shift AS shiftdtl,
          a.fullname AS jb_operator,
          b.fullname AS jb_incharge,
          c.fullname AS createdBy
          FROM tbl_factory_jb_laravel AS jb
          LEFT JOIN tbl_factory_fqc_laravel AS fqc
              ON fqc.fqc_QC = jb.jb_id
          LEFT JOIN hr_mstr_shift AS sh
              ON sh.id = jb.jb_shift
          LEFT JOIN tbl_factory_production_setup_laravel AS psl 
              ON psl.batchNo = jb.jb_batchNo
          LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
              ON psml.batchNo = psl.batchNo
          LEFT JOIN mstr_emp AS a 
              ON jb.jb_operator = a.id
          LEFT JOIN mstr_emp AS b
              ON jb.jb_incharge = b.id
          LEFT JOIN mstr_emp AS c
              ON jb.created_by = c.id
          WHERE $Condition
          GROUP BY jb.jb_barcode 
          ORDER BY jb.created_at DESC";
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $sql = "SELECT 
      fqc.*,
      psl.wattage,
      sh.shift AS shiftdtl,
      a.fullname AS fqc_operator,
      b.fullname AS fqc_incharge,
      c.fullname AS createdBy
      FROM tbl_factory_fqc_laravel AS fqc
      LEFT JOIN hr_mstr_shift AS sh 
          ON sh.id = fqc.fqc_shift
      LEFT JOIN tbl_factory_production_setup_laravel AS psl 
          ON psl.batchNo = fqc.fqc_batchNo
      LEFT JOIN mstr_emp AS a 
          ON fqc.fqc_operator = a.id 
      LEFT JOIN mstr_emp AS b 
          ON fqc.fqc_incharge = b.id
      LEFT JOIN mstr_emp AS c 
          ON fqc.created_by = c.id
      GROUP BY fqc.fqc_id
      ORDER BY fqc.created_at DESC";
      // dd($sql);
      $data['AllLaminatorLists'] = DB::select($sql);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.FinalQC.index', $data);
  }
  
  
  public function indexAll()
  {
      $data['menu'] = 'final-qc-all';

      $Condition = 'fqc.fqc_QC IS NULL AND jb.status = 1';
      $Cond = [];

      if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
          $Cond[] = "jb.created_by = '" . $_GET['createdBy'] . "'";
      }
      if (isset($_GET['operator']) && $_GET['operator'] != '') {
          $Cond[] = "jb.jb_operator = '" . $_GET['operator'] . "'";
      }
      if (isset($_GET['checker']) && $_GET['checker'] != '') {
          $Cond[] = "jb.jb_incharge = '" . $_GET['checker'] . "'";
      }
      if (isset($_GET['shift']) && $_GET['shift'] != '') {
          $Cond[] = "jb.jb_shift = '" . $_GET['shift'] . "'";
      }
      if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
          $Cond[] = "CAST(jb.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
      }
      if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
          $Cond[] = "CAST(jb.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
      }
      if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
          $Cond[] = "jb.jb_batchNo = '" . $_GET['batchNo'] . "'";
      }
      if (count($Cond) > 0) {
          $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
      }

      $sql = "SELECT 
          jb.*,
          psl.wattage,
          psml.size AS cellSize,
          sh.shift AS shiftdtl,
          a.fullname AS jb_operator,
          b.fullname AS jb_incharge,
          c.fullname AS createdBy
          FROM tbl_factory_jb_laravel AS jb
          LEFT JOIN tbl_factory_fqc_laravel AS fqc
              ON fqc.fqc_QC = jb.jb_id
          LEFT JOIN hr_mstr_shift AS sh
              ON sh.id = jb.jb_shift
          LEFT JOIN tbl_factory_production_setup_laravel AS psl 
              ON psl.batchNo = jb.jb_batchNo
          LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
              ON psml.batchNo = psl.batchNo
          LEFT JOIN mstr_emp AS a 
              ON jb.jb_operator = a.id
          LEFT JOIN mstr_emp AS b
              ON jb.jb_incharge = b.id
          LEFT JOIN mstr_emp AS c
              ON jb.created_by = c.id
          WHERE $Condition
          GROUP BY jb.jb_barcode 
          ORDER BY jb.created_at DESC";
      // dd($sql);
      $data['AllLists'] = DB::select($sql);

      $sql = "SELECT 
      fqc.*,
      psl.wattage,
      sh.shift AS shiftdtl,
      a.fullname AS fqc_operator,
      b.fullname AS fqc_incharge,
      c.fullname AS createdBy
      FROM tbl_factory_fqc_laravel AS fqc
      LEFT JOIN hr_mstr_shift AS sh 
          ON sh.id = fqc.fqc_shift
      LEFT JOIN tbl_factory_production_setup_laravel AS psl 
          ON psl.batchNo = fqc.fqc_batchNo
      LEFT JOIN mstr_emp AS a 
          ON fqc.fqc_operator = a.id 
      LEFT JOIN mstr_emp AS b 
          ON fqc.fqc_incharge = b.id
      LEFT JOIN mstr_emp AS c 
          ON fqc.created_by = c.id
      GROUP BY fqc.fqc_id
      ORDER BY fqc.created_at DESC";
      // dd($sql);
      $data['AllLaminatorLists'] = DB::select($sql);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.FinalQC.all-list', $data);
  }

  public function add()
    {
        $data['menu'] = 'final-qc';
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
        $data['DmgMachine'] = DB::table('master_type_dtls')
            ->select('master_type_dtls.*')
            ->where('master_type_dtls.parent_id', 47)
            ->get();
        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->get();
        $data['bushingNo'] = DB::table('tbl_factory_bushing_laravel as a')
            ->select('a.bushing_batchNo')
            ->distinct()
            ->get();

        $batchNo = request()->get('id');
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();


        

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.add', $data);
    }


    public function getBushingMaterial(Request $request)
    {
        $batchno = $request->input('q');
        // $batchno = DB::table('tbl_factory_bushing_laravel')
        //     ->where('bushing_id', $bushingno)
        //     ->value('bushing_batchNo');
        $bushingMaterial = DB::table('tbl_factory_production_setup_laravel as psl')
            ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->join('tbl_factory_material_master_laravel as m', 'm.id', '=', 'psml.material')
            ->select('m.title as matname', 'm.id as matid', 'psl.wattage', 'psml.size AS msize', 'psml.brand AS mbrand')
            ->where('psl.batchNo', $batchno)
            ->get();

        foreach ($bushingMaterial as $item)
            return response()->json([
                'batchno' => $batchno,
                'materials' => $bushingMaterial,
                'wattage' => $item->wattage ?? 'N/A',
                'size' => $item->msize ?? 'N/A',
                'brand' => $item->mbrand ?? 'N/A',
            ]);
    }

    public function validateBarCode(Request $request)
    {
        $barcode = $request->get('barCode');
        $btch_viewNo = $request->get('id');
        $action = $request->get('action') ?? '';
        $batchNo = $request->get('id') ?? '';
        
        if ($action === 'view') {
            $exists = DB::table('tbl_factory_bushing_laravel')
            ->select('bushing_logo')
            ->where('bushing_barCode', $barcode)
            ->where('bushing_batchNo', $batchNo)
            //->where('bushing_id', $btch_viewNo)
            ->first();
        } else {
            $exists = DB::table('tbl_factory_bushing_laravel')
            ->select('bushing_id' , 'bushing_logo')
            ->where('bushing_barCode', $barcode)
            ->where('bushing_batchNo', $batchNo)
            ->get();
        }
            
        // If action is 'view', we're in view mode - skip EL QC validation
        if ($action === 'view') {
            return response()->json([
                'status' => 'success',
                'bushing_logo' => $exists->bushing_logo ?? null,
                'message' => 'Barcode is valid (view mode).',
            ]);
        }
            
        // Check if barcode passed in 90Deg QC or Not
        $passExists = DB::table('tbl_factory_jb_laravel')
            ->where('jb_barcode', $barcode)
            ->where('jb_batchNo', $batchNo)
            ->exists(); 
            
        // Check if barcode already used in JB
        $elQcExists = DB::table('tbl_factory_fqc_laravel')
            ->where('fqc_barcode', $barcode)
            ->where('fqc_batchNo', $batchNo)
            ->exists();
    
        // If found in EL QC - INVALID
        if ($elQcExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in Final QC.',
            ]);
        }
        if (!$passExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not passed Junction Box against this batchno.',
            ]);
        }
        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'bushing_id' => $exists[0]->bushing_id ?? null,
                'bushing_logo' => $exists[0]->bushing_logo,
                'message' => 'Barcode is valid.',
            ]);
        }

        return response()->json(['exists' => $exists]);
    }


    public function insert(Request $request){


      if ($request->input('err') == '1') {
            return redirect()->back()->with('error', 'Please correct the errors before submitting the form.');
        } else {
            
            $Bexists = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_barCode', $request->input('barCode'))
            ->where('bushing_batchNo', $request->input('batchNo'))
            ->exists();
            $PreExists = DB::table('tbl_factory_jb_laravel')
            ->where('jb_barcode', $request->input('barCode'))
            ->where('jb_batchNo', $request->input('batchNo'))
            ->exists(); 
            
            if($Bexists == true && $PreExists == true){
            
                $exists = DB::table('tbl_factory_fqc_laravel')
                  ->where('fqc_barcode', $request->input('barCode'))
                  ->exists();
                  
                if($exists == false){
                    
                    $qcId = DB::table('tbl_factory_jb_laravel')
                    ->where('jb_barcode', $request->input('barCode'))
                    ->where('jb_batchNo', $request->input('batchNo'))
                  ->value('jb_id');
        
                    $id = date('YmdHis');
                    $data = array(
                        'fqc_id' => $id,
                        'fqc_date' => date('d-m-Y'),
                        'fqc_time' => date('H:i:s'),
                        'fqc_operator' => $request->input('operator'),
                        'fqc_source' => 'Junction Box',
                        'fqc_QC' => $qcId,
                        'fqc_batchNo' => $request->input('batchNo'),
                        'fqc_incharge' => $request->input('incharge'),
                        'fqc_cycle_no' => $request->input('cycleNo'),
                        'fqc_shift' => $request->input('shift'),
                        'fqc_plant' => $request->input('plant'),
                        'status' => $request->input('el_type'),
                        'fqc_pDefectRsn' => $request->input('p_reject_reason'),
                        'fqc_barcode' => $request->input('barCode'),
                        'created_by' => $request->session()->get('empId')
                    );
        
        
                    $res = FinalQC_Model::create($data);
                    
                    FinalQC_Hist_Model::create([
                        'fqc_id' => $id,
                        'action' => 'Raised',
                        'ip_address' => $this->getUserIP(),
                        'created_by' => auth()->id()
                    ]);
        
        
                    $cell_positions = $request->input('cell_position', []);
                    $cell_qtys = $request->input('cell_qty', []);
                    $dmgMat_reasons = $request->input('dmgMat_reason', []);
                    $defect_categories = $request->input('dmgMat_cat', []);
                    $res_prsns = $request->input('res_prsn', []);
                    $res_machines = $request->input('res_machine', []);
        
                    if ($request->input('el_type') === '0' && is_array($cell_positions) && count($cell_positions) > 0) {
                        foreach ($cell_positions as $i => $cell_no) {
                            // skip empty rows (optional)
                            if ($cell_no === null || $cell_no === '') {
                                continue;
                            }
        
                            $defectData = array(
                                'fqc_id' => $id,
                                'cell_no' => $cell_no,
                                'cell_qty' => $cell_qtys[$i] ?? null,
                                'defectRsn' => $dmgMat_reasons[$i] ?? null,
                                'defectCatgry' => $defect_categories[$i] ?? null,
                                'res_prsn' => $res_prsns[$i] ?? null,
                                'res_machine' => $res_machines[$i] ?? null,
                                'status' => '0'
                            );
        
                            FinalQC_Defect_Model::create($defectData);
                        }
                    }
                    
                    
                    //L I N K   W I T H   P R O D U C TI O N   S T O C K
                    
                    
                     
                     $batchRawData = DB::table('tbl_factory_production_setup_laravel')
                    ->where('batchNo', $request->input('batchNo'))
                    ->first();
              
              
                    
                    $data = array(
                        'userID'             => $request->session()->get('empId'),
                        'status'             => 0,
                        'Forward_Status'     => 0,
                        'Approve_status'     => 'APPROVE',
                        'Approve_Step'       => 1,
                        'Unit_Name'          => 130,
                        'Plant_Name'         => 743,
                        'Organization_Name'  => 4,
                        'BU_Name'            => 3,
                        'Shift'              => $batchRawData->fromShift,
                        'Production_Date'    => date('Y-m-d'), 
                        'Raw_Material'       => $batchRawData->finishGood,
                        'remarks'            => "Direct Stock Entry From FQC",
                        'UOM'                => 'Nos',
                        'Rate'               => '0',
                        'Quantity'           => 1,
                        'Total_amount'       => '0'
                    );
        
        
                    $prodEntry = Production::create($data);
                    
                    $prodEntryID = $prodEntry->id;
                    
                    $prev_sl_no = productionBatch::max('sl');
                    $slNo = $prev_sl_no+1;
                    $slAlfa = 'SLNO'.$slNo;
                    
                    $data = array(
                        'productionID'      => $prodEntryID,
                        'batch_no'          => 0,
                        'sl_no'             => 0,
                    );
                    
                    DB::table('production_batch')->insert([
                        'productionID' => $prodEntryID,
                        'batch_no'     => $request->input('batchNo'),
                        'sl_no'        => $slAlfa,
                        'serail_check' => $request->input('barCode'),
                        'batch'        => $request->input('batchNo'),
                        'sl'           => $slNo,
                        'created_at'   => now(), // Laravel helper for current timestamp
                        'updated_at'   => now(),
                    ]);
        
        
                    //$prodEntry = ProductionBatch::create($data);
                    
                    
                    
                    //L I N K   W I T H   P R O D U C TI O N   S T O C K
                    
                    
                    
                    
                    $lock = request()->input('lock');
                    $batchNo = request()->input('batchNo');
                    $oprtr = request()->input('operator');
                    $incherge = request()->input('incharge');
                    $shift = request()->input('shift');
                    $plant = request()->input('plant');
                    $page = request()->input('page');
                    if ($res->exists) {
                        if ($lock && $page) {
                            $url = 'production-lineup/final-qc/add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                            return redirect($url)->with('success', ' Final QC data stored successfully!');
                        } else {
                           //$url = ;
                            return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored successfully!');
                        }
                    }
                }else {
                   //$url = ;
                    return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored failed! Duplicate Barcode');
                }
            }else {
               //$url = ;
                return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored failed! Barcode Not Passed in Either Layout Setup or Junction Box');
            }
        }
    }


    public function view_fqc($id = null){
      
        //echo 'hi'; exit;
      $data['menu'] = 'junctionbox';
      $data['laminatorDetails'] = DB::table('tbl_factory_fqc_laravel as fqc')
          ->leftJoin('mstr_emp as emp1', 'fqc.fqc_operator', '=', 'emp1.id')
          ->leftJoin('mstr_emp as emp2', 'fqc.fqc_incharge', '=', 'emp2.id')
          ->leftJoin('hr_mstr_shift as sh', 'fqc.fqc_shift', '=', 'sh.id')
          ->select('fqc.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
          ->where('fqc.fqc_id', $id)
          ->first();
      $data['defectDetails'] = DB::table('tbl_factory_fqc_defect_laravel as def')
          ->select('def.*')
          ->where('def.fqc_id', $id)
          ->get();
      $data['laminatorHistory'] = DB::table('tbl_factory_fqc_history_laravel as history')
          ->select('history.*', 'emp.fullname as created_by')
          ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
          ->where('history.fqc_id', $id)
          ->get();
      $data['DmgRsn'] = DB::table('master_type_dtls')
          ->select('master_type_dtls.*')
          ->where('master_type_dtls.parent_id', 84)
          ->get();
      $data['DmgCat'] = DB::table('master_type_dtls')
          ->select('master_type_dtls.*')
          ->where('master_type_dtls.parent_id', 85)
          ->get();
      $data['DmgMachine'] = DB::table('master_type_dtls')
          ->select('master_type_dtls.*')
          ->where('master_type_dtls.parent_id', 88)
          ->get();
      $data['userList'] = DB::table('mstr_emp')
          ->select('mstr_emp.id', 'mstr_emp.fullname')
          ->where('mstr_emp.status', '1')
          ->get();
      $batchNo = $data['laminatorDetails']->fqc_batchNo;
      $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
          ->select('psl.cellRow', 'psl.celColumn')
          ->where('psl.batchNo', $batchNo)
          ->first();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
      return view('ProductionLineUp.FinalQC.view_fqc', $data);
    
    }
}
