<?php

namespace App\Http\Controllers\ProductionLineUp\Trimming;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{Trimming_Model, TrimmingDefect_Model, TrimmingHist_Model};

class Trimming_Controller extends Controller
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
        $data['menu'] = 'trimming-setup';

        $Condition = 'trimming.trimming_laminatorNo IS NULL AND laminator.status=1';
        $Cond = [];

        if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
            $Cond[] = "laminator.created_by = '" . $_GET['createdBy'] . "'";
        }
        if (isset($_GET['operator']) && $_GET['operator'] != '') {
            $Cond[] = "laminator.laminator_operator = '" . $_GET['operator'] . "'";
        }
        if (isset($_GET['checker']) && $_GET['checker'] != '') {
            $Cond[] = "laminator.laminator_incharge = '" . $_GET['checker'] . "'";
        }
        if (isset($_GET['shift']) && $_GET['shift'] != '') {
            $Cond[] = "laminator.laminator_shift = '" . $_GET['shift'] . "'";
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
            $Cond[] = "CAST(laminator.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
            $Cond[] = "CAST(laminator.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
        }
        if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
            $Cond[] = "laminator.laminator_batchNo = '" . $_GET['batchNo'] . "'";
        }
        if (count($Cond) > 0) {
            $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
        }

        $sql = "SELECT 
            laminator.*,
            psl.wattage,
            sh.shift AS shiftdtl,
            a.fullname AS laminator_operator,
            b.fullname AS laminator_incharge,
            c.fullname AS createdBy
            FROM tbl_factory_laminator_laravel AS laminator
            LEFT JOIN tbl_factory_trimming_laravel AS trimming
                ON trimming.trimming_laminatorNo = laminator.laminator_id
            LEFT JOIN hr_mstr_shift AS sh
                ON sh.id = laminator.laminator_shift
            LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = laminator.laminator_batchNo
            LEFT JOIN mstr_emp AS a 
                ON laminator.laminator_operator = a.id
            LEFT JOIN mstr_emp AS b
                ON laminator.laminator_incharge = b.id
            LEFT JOIN mstr_emp AS c
                ON laminator.created_by = c.id
            WHERE $Condition
            ORDER BY laminator.created_at DESC";
        // dd($sql);
        $data['AllLists'] = DB::select($sql);

        $sql = "SELECT 
        trimming.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS trimming_operator,
        b.fullname AS laminator_incharge,
        c.fullname AS createdBy
        FROM tbl_factory_trimming_laravel AS trimming
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = trimming.trimming_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = trimming.trimming_batchNo
        LEFT JOIN mstr_emp AS a 
            ON trimming.trimming_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON trimming.trimming_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON trimming.created_by = c.id
        ORDER BY trimming.created_at DESC";
        // dd($sql);
        $data['AllLaminatorLists'] = DB::select($sql);
$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.Trimming.index', $data);
    }


    public function add_trimming()
    {
        $data['menu'] = 'trimming-setup';
        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();
        $data['PlantMaster'] = DB::table('master_type_dtls')
            ->select('master_type_dtls.*')
            ->where('master_type_dtls.parent_id', 83)
            ->get();
        $data['materialMaster'] = DB::table('tbl_factory_material_master_laravel')
            ->select('id', 'title')
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
            ->get();
        $data['bushingNo'] = DB::table('tbl_factory_bushing_laravel as a')
            ->select('a.bushing_batchNo')
            ->distinct()
            ->get();
$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.Trimming.add_trimming', $data);
    }


    public function getLaminatorId(Request $request)
    {
        $batchNo = $request->q;

        $sql = "SELECT 
            laminator.laminator_id
            FROM tbl_factory_laminator_laravel AS laminator
            LEFT JOIN tbl_factory_trimming_laravel AS trimming
                ON trimming.trimming_laminatorNo = laminator.laminator_id
            WHERE trimming.trimming_laminatorNo IS NULL AND laminator.status=1 AND laminator.laminator_batchNo = $batchNo
            ORDER BY laminator.created_at DESC";

        $elqcData = DB::select($sql);;

        if ($elqcData) {
            return response()->json(['elqc_ids' => $elqcData]);
        } else {
            return response()->json(['elqc_ids' => null]);
        }
    }

    
    public function validateRFID(Request $request)
    {
        $rfid = $request->input('rfid');
        $batchNo = $request->input('id');
        $exists = DB::table('tbl_factory_laminator_laravel')
            ->where('laminator_rfid', $rfid)
            ->where('laminator_batchNo', $batchNo)
            ->get();

        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'RFID is valid.',
            ]);
        }

        //return response()->json(['exists' => $exists]);
    }

    public function validateBarCode(Request $request)
    {
        $barcode = $request->get('barCode');
        $batchNo = $request->get('id');
        $exists = DB::table('tbl_factory_laminator_laravel')
            ->where('laminator_barcode', $barcode)
            ->where('laminator_batchNo', $batchNo)
            ->get();
        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'BarCode is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'BarCode is valid.',
            ]);
        }
    }


    public function insert(Request $request)
    {
      //print_r($_POST);exit;

      
        if ($request->input('err') == '1') {
            return redirect()->back()->with('error', 'Please correct the errors before submitting the form.');
        } else {
            $id = date('YmdHis');
            $data = array(
                'trimming_id' => $id,
                'trimming_date' => date('d-m-Y'),
                'trimming_time' => date('H:i:s'),
                'trimming_operator' => $request->input('operator'),
                'trimming_source' => 'Bushing',
                'trimming_laminatorNo' => $request->input('bushingNo'),
                'trimming_batchNo' => $request->input('batchNo'),
                'trimming_incharge' => $request->input('incharge'),
                'trimming_cycle_no' => $request->input('cycleNo'),
                'trimming_shift' => $request->input('shift'),
                'trimming_plant' => $request->input('plant'),
                'status' => $request->input('el_type'),
                'trimming_rfid' => $request->input('rfid'),
                'trimming_barcode' => $request->input('barCode'),
                'created_by' => $request->session()->get('empId')
            );

            $res = Trimming_Model::create($data);
            
            TrimmingHist_Model::create([
                'trimming_id' => $id,
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
                        'trimming_Id' => $id,
                        'cell_no' => $cell_no,
                        'cell_qty' => $cell_qtys[$i] ?? null,
                        'defectRsn' => $dmgMat_reasons[$i] ?? null,
                        'defectCatgry' => $defect_categories[$i] ?? null,
                        'res_prsn' => $res_prsns[$i] ?? null,
                        'res_machine' => $res_machines[$i] ?? null,
                    );

                    TrimmingDefect_Model::create($defectData);
                }
            }
            $lock = request()->input('lock');
            $oprtr = request()->input('operator');
            $incherge = request()->input('incharge');
            $shift = request()->input('shift');
            $plant = request()->input('plant');
            $page = request()->input('page');
            if ($res->exists) {
                if ($lock && $page) {
                    $url = 'production-lineup/trimming/add?page=ALL&lock=1&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                    return redirect($url)->with('success', ' Trimming data stored successfully!');
                } else {
                    return redirect()->back()->with('success', 'Trimming data stored successfully!');
                }
            }
        }
    }


    public function damage_report()
    {
        $data['menu'] = 'elqc-damage';

        $sql = "SELECT 
        laminator.*,
        def.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS laminator_operator,
        b.fullname AS laminator_incharge,
        c.fullname AS createdBy,
        d.fullname AS rsponsible_person,
        (SELECT SUM(d2.cell_qty)
        FROM tbl_factory_laminator_defect_laravel d2
        WHERE d2.laminator_Id = laminator.laminator_id
        ) AS no_of_cell_damage
        FROM tbl_factory_laminator_laravel AS laminator
        JOIN tbl_factory_laminator_defect_laravel AS def
            ON laminator.laminator_id = def.laminator_Id
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = laminator.laminator_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = laminator.laminator_batchNo
        LEFT JOIN mstr_emp AS a 
            ON laminator.laminator_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON laminator.laminator_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON laminator.created_by = c.id
        LEFT JOIN mstr_emp AS d 
            ON def.res_prsn = d.id
        WHERE laminator.status = '0'
        ORDER BY def.def_id DESC";

        // dd($sql);
        $data['AllDamageLists'] = DB::select($sql);
        $data['menu'] = 'trimming-damage';
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.laminator_damage', $data);
    }
}
