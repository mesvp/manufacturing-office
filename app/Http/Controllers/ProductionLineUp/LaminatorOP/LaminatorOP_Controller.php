<?php

namespace App\Http\Controllers\ProductionLineUp\LaminatorOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{Laminator_OP, Laminator_OP_Defect, Laminator_OP_History};

class LaminatorOP_Controller extends Controller
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
        $data['menu'] = 'laminator-op';

        $Condition = "laminator.laminator_elqcNo IS NULL OR (laminator.rwrk_status = '1' AND laminator.status = '0')";
        $Cond = [];

        if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
            $Cond[] = "elqc.created_by = '" . $_GET['createdBy'] . "'";
        }
        if (isset($_GET['operator']) && $_GET['operator'] != '') {
            $Cond[] = "elqc.elqc_operator = '" . $_GET['operator'] . "'";
        }
        if (isset($_GET['checker']) && $_GET['checker'] != '') {
            $Cond[] = "elqc.elqc_incharge = '" . $_GET['checker'] . "'";
        }
        if (isset($_GET['shift']) && $_GET['shift'] != '') {
            $Cond[] = "elqc.elqc_shift = '" . $_GET['shift'] . "'";
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
            $Cond[] = "CAST(elqc.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
            $Cond[] = "CAST(elqc.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
        }
        if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
            $Cond[] = "laminator.elqc_batchNo = '" . $_GET['batchNo'] . "'";
        }
        if (count($Cond) > 0) {
            $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
        }

        // $sql = "SELECT 
        //     elqc.*,
        //     psl.wattage,
        //     sh.shift AS shiftdtl,
        //     a.fullname AS laminator_operator,
        //     b.fullname AS laminator_incharge,
        //     c.fullname AS createdBy
        //     FROM tbl_factory_el_qc_laravel AS elqc
        //     LEFT JOIN tbl_factory_laminator_laravel AS laminator
        //         ON laminator.laminator_elqcNo = elqc.elqc_id
        //     LEFT JOIN hr_mstr_shift AS sh
        //         ON sh.id = elqc.elqc_shift
        //     LEFT JOIN tbl_factory_production_setup_laravel AS psl 
        //         ON psl.batchNo = elqc.elqc_batchNo
        //     LEFT JOIN mstr_emp AS a 
        //         ON elqc.elqc_operator = a.id
        //     LEFT JOIN mstr_emp AS b
        //         ON elqc.elqc_incharge = b.id
        //     LEFT JOIN mstr_emp AS c
        //         ON elqc.created_by = c.id
        //     WHERE $Condition
        //     GROUP BY elqc.elqc_id 
        //     ORDER BY elqc.created_at DESC";
            
        $sql = "SELECT 
            elqc.*,
            laminator.laminator_id,laminator.rwrk_status as rwrk_sts,laminator.status as sts,laminator.laminator_source,
            psl.wattage,
            psml.size AS cellSize,
            sh.shift AS shiftdtl,
            a.fullname AS laminator_operator,
            b.fullname AS laminator_incharge,
            c.fullname AS createdBy
        FROM tbl_factory_el_qc_laravel AS elqc
        LEFT JOIN tbl_factory_laminator_laravel AS laminator
            ON laminator.laminator_elqcNo = elqc.elqc_id
        LEFT JOIN hr_mstr_shift AS sh
            ON sh.id = elqc.elqc_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = elqc.elqc_batchNo
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id
        LEFT JOIN mstr_emp AS b
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c
            ON elqc.created_by = c.id
        WHERE (
            laminator.laminator_elqcNo IS NULL
            OR (laminator.status = '0' AND laminator.rwrk_status = '1')
        )
        AND (elqc.status = '1' AND elqc.rwrk_status = '1')
        GROUP BY elqc.elqc_id
        ORDER BY elqc.created_at DESC";

        // dd($sql);
        $data['AllLists'] = DB::select($sql);

        $sql = "SELECT 
        laminator.*,
        psl.wattage,
        psml.size AS cellSize,
        sh.shift AS shiftdtl,
        a.fullname AS laminator_operator,
        b.fullname AS laminator_incharge,
        c.fullname AS createdBy,
        (SELECT SUM(d2.cell_qty)
         FROM tbl_factory_laminator_defect_laravel d2
         WHERE d2.laminator_Id = laminator.laminator_id
        ) AS no_of_cell_damage
        FROM tbl_factory_laminator_laravel AS laminator
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = laminator.laminator_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = laminator.laminator_batchNo
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON laminator.laminator_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON laminator.laminator_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON laminator.created_by = c.id
        GROUP BY laminator.laminator_id
        ORDER BY laminator.created_at DESC";
        // dd($sql);
        $data['AllLaminatorLists'] = DB::select($sql);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.index', $data);
    }

    public function laminator_damage_report()
    {
        $data['menu'] = 'elqc-damage';

        $sql = "SELECT 
        laminator.*,
        def.*,
        psl.wattage,
        psml.size AS cellSize,
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
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON laminator.laminator_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON laminator.laminator_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON laminator.created_by = c.id
        LEFT JOIN mstr_emp AS d 
            ON def.res_prsn = d.id
        WHERE laminator.status = '2'
        GROUP BY def.def_id
        ORDER BY def.def_id DESC";

        // dd($sql);
        $data['AllDamageLists'] = DB::select($sql);
        $data['menu'] = 'laminator-damage-report';
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.laminator_damage', $data);
    }

    public function add_laminator_op()
    {
        $data['menu'] = 'laminator-op';
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
            ->where('mstr_emp.status', '1')
            ->get();
        $data['bushingNo'] = DB::table('tbl_factory_el_qc_laravel as a')
            ->select('a.elqc_batchNo')
            ->where('a.status', '1')
            ->where('a.rwrk_status', '1')
            ->distinct()
            ->get();
        $batchNo = request()->get('id');
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();
        // if(isset($_GET['id'])) {
        // $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
        //     ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
        //     ->join('tbl_factory_material_master_laravel as m', 'm.id', '=', 'psml.material')
        //     ->select('m.title as matname', 'm.id as matid', 'psl.wattage', 'psml.size AS msize', 'psml.brand AS mbrand', 'psl.cellRow', 'psl.celColumn')
        //     ->where('psl.batchNo', $_GET['id'])
        //     ->get();
            
        // }
        
        //dd($data);exit;
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.add_laminator_op', $data);
    }

    public function insert(Request $request)
    {
        // dd($request->input('err'));
        if ($request->input('err') == '1') {
            return redirect()->back()->with('error', 'Please correct the errors before submitting the form.');
        } else {
            $id = date('YmdHis');
            $data = array(
                'laminator_id' => $id,
                'laminator_date' => date('d-m-Y'),
                'laminator_time' => date('H:i:s'),
                'laminator_operator' => $request->input('operator'),
                'laminator_source' => 'EL QC',
                'laminator_elqcNo' => $request->input('bushingNo'),
                'laminator_batchNo' => $request->input('batchNo'),
                'laminator_incharge' => $request->input('incharge'),
                'laminator_cycle_no' => $request->input('cycleNo'),
                'laminator_shift' => $request->input('shift'),
                'laminator_plant' => $request->input('plant'),
                'status' => $request->input('el_type'),
                'rwrk_status' => ($request->input('el_type') == '1') ? '1' : '',
                'laminator_rfid' => $request->input('rfid'),
                'laminator_barcode' => $request->input('barCode'),
                'created_by' => $request->session()->get('empId')
            );
            
            $res = Laminator_OP::create($data);
            
            Laminator_OP_History::create([
                'laminator_id' => $id,
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
                        'laminator_Id' => $id,
                        'cell_no' => $cell_no,
                        'cell_qty' => $cell_qtys[$i] ?? null,
                        'defectRsn' => $dmgMat_reasons[$i] ?? null,
                        'defectCatgry' => $defect_categories[$i] ?? null,
                        'res_prsn' => $res_prsns[$i] ?? null,
                        'res_machine' => $res_machines[$i] ?? null,
                        'status' => '0'
                    );
                    Laminator_OP_Defect::create($defectData);
                }
            }
            $lock = request()->input('lock');
            $batchNo = request()->input('batchNo');
            $oprtr = request()->input('operator');
            $incherge = request()->input('incharge');
            $shift = request()->input('shift');
            $plant = request()->input('plant');
            $page = request()->input('page');
            if ($res->exists) {
                if ($lock && $page) {
                    $url = 'production-lineup/laminator-op-add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                    return redirect($url)->with('success', ' Laminator data stored successfully!');
                } else {
                    return redirect('production-lineup/laminator-op')->with('success', 'Laminator data stored successfully!');
                }
            }
        }
    }

    public function view_laminator_op($id = null)
    {
        $data['menu'] = 'laminator-op';
        $data['laminatorDetails'] = DB::table('tbl_factory_laminator_laravel as laminator')
            ->leftJoin('mstr_emp as emp1', 'laminator.laminator_operator', '=', 'emp1.id')
            ->leftJoin('mstr_emp as emp2', 'laminator.laminator_incharge', '=', 'emp2.id')
            ->leftJoin('hr_mstr_shift as sh', 'laminator.laminator_shift', '=', 'sh.id')
            ->select('laminator.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
            ->where('laminator.laminator_id', $id)
            ->first();
        $data['defectDetails'] = DB::table('tbl_factory_laminator_defect_laravel as def')
            ->select('def.*', 'emp.fullname as responsible_person')
            ->leftJoin('mstr_emp as emp', 'def.res_prsn', '=', 'emp.id')
            ->where('def.laminator_Id', $id)
            ->get();
        $data['laminatorHistory'] = DB::table('tbl_factory_laminator_history_laravel as history')
            ->select('history.*', 'emp.fullname as created_by')
            ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
            ->where('history.laminator_id', $id)
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
        $batchNo = $data['laminatorDetails']->laminator_batchNo;
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();
            
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.view_laminator_op', $data);
    }

    public function getelqcId(Request $request)
    {
        $batchNo = $request->q;
        $elqcData = DB::table('tbl_factory_el_qc_laravel')
        ->select('elqc_id')
        ->where('elqc_batchNo', $batchNo)
        ->whereNotIn('elqc_id', function ($query) {
            $query->select('laminator_elqcNo')
                  ->from('tbl_factory_laminator_laravel');
        })
        ->get();
        if ($elqcData) {
            return response()->json(['elqc_ids' => $elqcData]);
        } else {
            return response()->json(['elqc_ids' => null]);
        }
    }

    public function getBushingMaterial(Request $request)
    {
        $batchno = $request->input('q');
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

    public function getDefBatchId(Request $request)
    {
        $batchNo = $request->input('q');
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();
        return response()->json(['defBatchId' => $data['bushingMaterial']]);
    }

    public function checkCycleSlno(Request $request)
    {
        $slno = (int) $request->input('slno');
        $date = date('d-m-Y', strtotime($request->input('date')));
        $shift = $request->input('shift');

        // Fetch all existing cycle numbers for same date+shift
        $records = DB::table('tbl_factory_laminator_laravel')
            ->where('laminator_date', $date)
            ->where('laminator_shift', $shift)
            ->pluck('laminator_cycle_no');

        // Total rows reached 4
        if ($records->count() >= 4) {
            return response()->json([
                'status' => 'error',
                'message' => "Only 4 entries are allowed for this Date & Shift."
            ]);
        }

        // If NO previous entries
        if ($records->count() === 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Valid cycle number.'
            ]);
        }

        // Get minimum available cycle number
        $minCycle = $records->min();

        // If user enters a number LOWER than minimum
        if ($slno < $minCycle) {
            return response()->json([
                'status' => 'error',
                'message' => "Cycle No cannot be less than existing minimum value ($minCycle).",
                'min_cycle' => $minCycle
            ]);
        }

        // All good
        return response()->json([
            'status' => 'success',
            'message' => 'Valid cycle number.'
        ]);
    }


    public function laminator_op_rework()
    {
        $data['menu'] = 'laminator-op-rework';

        $sql = "SELECT 
        laminator.*,
        psl.wattage,
        psml.size AS cellSize,
        bol.bushing_batchNo,
        bol.bushing_barCode, 
        bol.bushing_rfid,
        (SELECT SUM(d2.cell_qty)
        FROM  tbl_factory_laminator_defect_laravel d2
        WHERE d2.laminator_Id = laminator.laminator_id
        ) AS no_of_cell_damage,
        sh.shift AS shiftdtl,
        a.fullname AS laminator_operator,
        b.fullname AS laminator_incharge,
        c.fullname AS createdBy
        FROM tbl_factory_laminator_laravel AS laminator
        LEFT JOIN tbl_factory_laminator_defect_laravel AS def
            ON laminator.laminator_id = def.laminator_Id
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = laminator.laminator_shift
        LEFT JOIN tbl_factory_bushing_laravel AS bol 
            ON bol.bushing_batchNo = laminator.laminator_batchNo
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = bol.bushing_batchNo
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
        ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON laminator.laminator_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON laminator.laminator_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON laminator.created_by = c.id
        WHERE laminator.status = '0' AND (laminator.rwrk_status = '' OR laminator.rwrk_status IS NULL)
        GROUP BY laminator.laminator_id
        ORDER BY laminator.created_at DESC";
        // dd($sql);
        $data['AllOPReworkLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.LaminatorOP.laminator_op_rework', $data);
    }

    public function update_laminator_op(Request $request, $id)
    {
        $rwrk_status = $request->input('rwrk_status');
        $rwrk_pg = $request->input('rwrk_pg');
        if($rwrk_pg){
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_laminator_laravel')
                    ->where('laminator_id', $id)
                    ->update(['laminator_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '1']);
                DB::table('tbl_factory_laminator_defect_laravel')->where('laminator_Id', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_laminator_history_laravel')->insert([
                    'laminator_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_laminator_laravel')
                    ->where('laminator_id', $id)
                    ->update(['laminator_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '0']);
                DB::table('tbl_factory_laminator_history_laravel')->insert([
                    'laminator_id' => $id,
                    'action' => 'Reject',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_laminator_laravel')
                    ->where('laminator_id', $id)
                    ->update(['laminator_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '0']);
                DB::table('tbl_factory_laminator_defect_laravel')->where('laminator_Id', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_laminator_history_laravel')->insert([
                    'laminator_id' => $id,
                    'action' => 'Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_laminator_laravel')
                    ->where('laminator_id', $id)
                    ->update(['laminator_source' => 'ReWork', 'rwrk_status' => '2', 'status' => '2']);
                DB::table('tbl_factory_laminator_history_laravel')->insert([
                    'laminator_id' => $id,
                    'action' => 'ReWork Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        if($rwrk_pg){
            return redirect('production-lineup/laminator-op')->with('success', 'El QC rework status updated successfully!');
        } else {
            return redirect('production-lineup/laminator-op-rework')->with('success', 'Laminator OP rework status updated successfully!');
        }
    }
    
    public function validateRFID(Request $request)
    {
        $rfid = $request->input('rfid');
        $batchNo = $request->input('id');
        $exists = DB::table('tbl_factory_el_qc_laravel')
            ->where('elqc_rfid', $rfid)
            ->where('elqc_batchNo', $batchNo)
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

        return response()->json(['exists' => $exists]);
    }

    public function validateBarCode(Request $request)
    {
        $barcode = $request->get('barCode');
        $batchNo = $request->get('id');
        $exists = DB::table('tbl_factory_el_qc_laravel')
            ->select('tbl_factory_el_qc_laravel.elqc_id')
            ->where('elqc_barcode', $barcode)
            ->where('elqc_batchNo', $batchNo)
            ->get();
            
        // Check if barcode already used in Laminator
        $laminatorExists = DB::table('tbl_factory_laminator_laravel')
            ->where('laminator_barcode', $barcode)
            ->where('laminator_batchNo', $batchNo)
            ->exists();
    
        // If found in Laminator → INVALID
        if ($laminatorExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in Laminator.',
            ]);
        }
        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'BarCode is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'elqc_id' => $exists[0]->elqc_id ?? null,
                'message' => 'BarCode is valid.',
            ]);
        }
    }
    
    public function fetchRFIDBar(Request $request)
    {
        $batchNo = $request->input('batch_No');
        $bushingNo = $request->input('bushingNo');
        
        $RFIDBardtls = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select('elqc.elqc_rfid','elqc.elqc_barcode')
            ->where('elqc.elqc_batchNo', $batchNo)
            ->where('elqc.elqc_id', $bushingNo)
            ->first();
            
        if (!$RFIDBardtls) {
            return response()->json([
                'rfid' => null,
                'barcode' => null,
                'message' => 'No record found'
            ], 404);
        }

        return response()->json(['rfid' => $RFIDBardtls->elqc_rfid, 'barcode' => $RFIDBardtls->elqc_barcode]);
    }
}
