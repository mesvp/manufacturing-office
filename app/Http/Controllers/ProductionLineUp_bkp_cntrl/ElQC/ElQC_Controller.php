<?php

namespace App\Http\Controllers\ProductionLineUp\ElQC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{EL_QC, EL_QC_Defect, EL_QC_History};

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
        $data['menu'] = 'elqc-setup';

        $Cond = [];
        $Condition = "(
            elqc.elqc_bushingNo IS NULL
            OR (elqc.rwrk_status = '1' AND elqc.status = '0')
        ) 
        AND bol.bushing_hasDamage = 'No'";


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
            $Cond[] = "elqc.elqc_batchNo = '" . $_GET['batchNo'] . "'";
        }
        if (count($Cond) > 0) {
            $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
        }
        $sql = "SELECT 
            bol.*,
            elqc.elqc_id,elqc.rwrk_status,elqc.status,elqc.elqc_source,
            psl.wattage,
            sh.shift AS shiftdtl,
            a.fullname AS bushing_operator,
            b.fullname AS bushing_incherge,
            c.fullname AS createdBy
            FROM tbl_factory_bushing_laravel AS bol
            LEFT JOIN tbl_factory_el_qc_laravel AS elqc
            ON bol.bushing_id = elqc.elqc_bushingNo
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
            GROUP BY bol.bushing_id 
            ORDER BY bol.created_at DESC";
        // dd($sql);
        $data['AllLists'] = DB::select($sql);


        $sql = "SELECT 
        elqc.*,
        psl.wattage,
        bol.bushing_id,
        sh.shift AS shiftdtl,
        a.fullname AS elqc_operator,
        b.fullname AS elqc_incharge,
        c.fullname AS createdBy,
        (SELECT SUM(d2.cell_qty)
         FROM tbl_factory_el_qc_defect_laravel d2
         WHERE d2.elqcId = elqc.elqc_id
        ) AS no_of_cell_damage
        FROM tbl_factory_el_qc_laravel AS elqc
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = elqc.elqc_shift
        JOIN tbl_factory_bushing_laravel AS bol 
            ON bol.bushing_id = elqc.elqc_bushingNo
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = bol.bushing_batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON elqc.created_by = c.id
        GROUP BY elqc.elqc_id
        ORDER BY elqc.created_at DESC";
        // dd($sql);
        $data['AllELQCLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.index', $data);
    }

    public function add_el_qc()
    {
        $data['menu'] = 'elqc-setup';
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
        return view('ProductionLineUp.ElQC.add_el_qc', $data);
    }

    public function view_el_qc($id)
    {
        $data['menu'] = 'elqc-rework';
        $data['elqcDetails'] = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->leftJoin('mstr_emp as emp1', 'elqc.elqc_operator', '=', 'emp1.id')
            ->leftJoin('mstr_emp as emp2', 'elqc.elqc_incharge', '=', 'emp2.id')
            ->leftJoin('hr_mstr_shift as sh', 'elqc.elqc_shift', '=', 'sh.id')
            ->select('elqc.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
            ->where('elqc.elqc_id', $id)
            ->first();
        $data['defectDetails'] = DB::table('tbl_factory_el_qc_defect_laravel as def')
            ->select('def.*', 'emp.fullname as responsible_person')
            ->leftJoin('mstr_emp as emp', 'def.res_prsn', '=', 'emp.id')
            ->where('def.elqcId', $id)
            ->get();

        $data['elqcHistory'] = DB::table('tbl_factory_el_qc_history_laravel as history')
            ->select('history.*', 'emp.fullname as created_by')
            ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
            ->where('history.el_qc_id', $id)
            ->get();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_view', $data);
    }

    public function store_el_qc(Request $request)
    {
        // dd($request->all());
        $id = date('YmdHis');
        $data = array(
            'elqc_id' => $id,
            'elqc_date' => date('d-m-Y'),
            'elqc_time' => date('H:i:s'),
            'elqc_operator' => $request->input('operator'),
            'elqc_source' => 'Bushing',
            'elqc_bushingNo' => $request->input('bushingNo'),
            'elqc_batchNo' => $request->input('batchNo'),
            'elqc_incharge' => $request->input('incharge'),
            'elqc_shift' => $request->input('shift'),
            'elqc_plant' => $request->input('plant'),
            'status' => $request->input('el_type'),
            'rwrk_status' => ($request->input('el_type') == '1') ? '1' : '',
            'elqc_rfid' => $request->input('rfid'),
            'elqc_barcode' => $request->input('barCode'),
            'created_by' => $request->session()->get('empId')
        );

        $res = EL_QC::create($data);
        EL_QC_History::create([
            'el_qc_id' => $id,
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
                    'elqcId' => $id,
                    'cell_no' => $cell_no,
                    'cell_qty' => $cell_qtys[$i] ?? null,
                    'defectRsn' => $dmgMat_reasons[$i] ?? null,
                    'defectCatgry' => $defect_categories[$i] ?? null,
                    'res_prsn' => $res_prsns[$i] ?? null,
                    'res_machine' => $res_machines[$i] ?? null,
                    'status' => '0'
                );

                EL_QC_Defect::create($defectData);
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
                $url = 'production-lineup/el-qc-add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                return redirect($url)->with('success', 'El QC data stored successfully!');
            } else {
                return redirect('production-lineup/el_qc')->with('success', 'El QC data stored successfully!');
            }
        }
    }
    public function getBushingMaterial(Request $request)
    {
        $bushingno = $request->input('q');
        $batchno = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_id', $bushingno)
            ->value('bushing_batchNo');
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

    public function validateRFID(Request $request)
    {
        $rfid = $request->input('rfid');
        $batchNo = $request->input('id');
        $exists = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_rfid', $rfid)
            ->where('bushing_batchNo', $batchNo)
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
        $exists = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_barCode', $barcode)
            ->where('bushing_batchNo', $batchNo)
            ->get();
        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Barcode is valid.',
            ]);
        }

        return response()->json(['exists' => $exists]);
    }

    public function el_qc_rework()
    {
        $data['menu'] = 'elqc-rework';

        $sql = "SELECT 
        elqc.*,
        psl.wattage,
        bol.bushing_batchNo,
        bol.bushing_barCode, 
        bol.bushing_rfid,
        (SELECT SUM(d2.cell_qty)
        FROM tbl_factory_el_qc_defect_laravel d2
        WHERE d2.elqcId = elqc.elqc_id
        ) AS no_of_cell_damage,
        sh.shift AS shiftdtl,
        a.fullname AS elqc_operator,
        b.fullname AS elqc_incharge,
        c.fullname AS createdBy
        FROM tbl_factory_el_qc_laravel AS elqc
        LEFT JOIN tbl_factory_el_qc_defect_laravel AS def
            ON elqc.elqc_id = def.elqcId
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = elqc.elqc_shift
        LEFT JOIN tbl_factory_bushing_laravel AS bol 
            ON bol.bushing_batchNo = elqc.elqc_batchNo
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = bol.bushing_batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON elqc.created_by = c.id
        WHERE elqc.status = '0' AND (elqc.rwrk_status = '' OR elqc.rwrk_status IS NULL)
        GROUP BY elqc.elqc_id
        ORDER BY elqc.created_at DESC";
        // dd($sql);
        $data['AllELQCReworkLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_rework', $data);
    }

    public function el_qc_damage()
    {
        $data['menu'] = 'elqc-damage';

        $sql = "SELECT 
        elqc.*,
        def.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS elqc_operator,
        b.fullname AS elqc_incharge,
        c.fullname AS createdBy,
        d.fullname AS rsponsible_person,
        (SELECT SUM(d2.cell_qty)
        FROM tbl_factory_el_qc_defect_laravel d2
        WHERE d2.elqcId = elqc.elqc_id
        ) AS no_of_cell_damage
        FROM tbl_factory_el_qc_laravel AS elqc
        JOIN tbl_factory_el_qc_defect_laravel AS def
            ON elqc.elqc_id = def.elqcId
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = elqc.elqc_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = elqc.elqc_batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON elqc.created_by = c.id
        LEFT JOIN mstr_emp AS d 
            ON def.res_prsn = d.id
        WHERE elqc.status = '2'
        ORDER BY def.def_id DESC";

        // dd($sql);
        $data['AllELQCDamageLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_damage', $data);
    }

    public function update_el_qc(Request $request, $id)
    {
        $rwrk_status = $request->input('rwrk_status');
        $rwrk_pg = $request->input('rwrk_pg');
        if($rwrk_pg){
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '1']);
                DB::table('tbl_factory_el_qc_defect_laravel')->where('elqcId', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '2', 'status' => '2']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Reject',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '0']);
                DB::table('tbl_factory_el_qc_defect_laravel')->where('elqcId', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '2', 'status' => '2']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Reject',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        if($rwrk_pg){
        return redirect('production-lineup/el_qc')->with('success', 'El QC rework status updated successfully!');
        } else {
        return redirect('production-lineup/el_qc_rework')->with('success', 'El QC rework status updated successfully!');
        }
    }

    public function getBushingId(Request $request)
    {
        $batchNo = $request->input('q');

        $bushingId = DB::table('tbl_factory_bushing_laravel as bol')
            ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
            ->select('bol.bushing_id')
            ->where('bol.bushing_batchNo', $batchNo)
            ->where(function ($query) {
                $query->whereNull('elqc.elqc_bushingNo')
                    ->where('bol.bushing_hasDamage', 'No');
            })
            ->get();

        return response()->json(['bushingIds' => $bushingId]);
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
    
    public function fetchRFIDBar(Request $request)
    {
        $batchNo = $request->input('batch_No');
        $bushingNo = $request->input('bushingNo');

        $RFIDBardtls = DB::table('tbl_factory_bushing_laravel as bol')
            ->select('bol.bushing_rfid','bol.bushing_barCode')
            ->where('bol.bushing_batchNo', $batchNo)
            ->where('bol.bushing_id', $bushingNo)
            ->first();

        return response()->json(['rfid' => $RFIDBardtls->bushing_rfid, 'barcode' => $RFIDBardtls->bushing_barCode]);
    }
}
