<?php

namespace App\Http\Controllers\ProductionLineUp\Framing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{Framing_Model, FramingDamage_Model, FramingHistory_Model};

class Framing_Controller extends Controller
{
    //
    
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
        $data['menu'] = 'framing';

        $Condition = 'framing.framing_QC IS NULL AND ninetydeg.status=1';
        $Cond = [];

        if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
            $Cond[] = "ninetydeg.created_by = '" . $_GET['createdBy'] . "'";
        }
        if (isset($_GET['operator']) && $_GET['operator'] != '') {
            $Cond[] = "ninetydeg.ninetydeg_operator = '" . $_GET['operator'] . "'";
        }
        if (isset($_GET['checker']) && $_GET['checker'] != '') {
            $Cond[] = "ninetydeg.ninetydeg_incharge = '" . $_GET['checker'] . "'";
        }
        if (isset($_GET['shift']) && $_GET['shift'] != '') {
            $Cond[] = "ninetydeg.ninetydeg_shift = '" . $_GET['shift'] . "'";
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
            $Cond[] = "CAST(ninetydeg.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
            $Cond[] = "CAST(ninetydeg.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
        }
        if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
            $Cond[] = "ninetydeg.ninetydeg_batchNo = '" . $_GET['batchNo'] . "'";
        }
        if (count($Cond) > 0) {
            $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
        }

        $sql = "SELECT 
            ninetydeg.*,
            psl.wattage,
            sh.shift AS shiftdtl,
            a.fullname AS ninetydeg_operator,
            b.fullname AS ninetydeg_incharge,
            c.fullname AS createdBy
            FROM tbl_factory_ninetydeg_laravel AS ninetydeg
            LEFT JOIN tbl_factory_framing_laravel AS framing
                ON framing.framing_QC = ninetydeg.ninetydeg_id
            LEFT JOIN hr_mstr_shift AS sh
                ON sh.id = ninetydeg.ninetydeg_shift
            LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = ninetydeg.ninetydeg_batchNo
            LEFT JOIN mstr_emp AS a 
                ON ninetydeg.ninetydeg_operator = a.id
            LEFT JOIN mstr_emp AS b
                ON ninetydeg.ninetydeg_incharge = b.id
            LEFT JOIN mstr_emp AS c
                ON ninetydeg.created_by = c.id
            WHERE $Condition
            ORDER BY ninetydeg.created_at DESC";
        // dd($sql);
        $data['AllLists'] = DB::select($sql);

        $sql = "SELECT 
        framing.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS laminator_operator,
        b.fullname AS laminator_incharge,
        c.fullname AS createdBy
        FROM tbl_factory_framing_laravel AS framing
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = framing.framing_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = framing.framing_batchNo
        LEFT JOIN mstr_emp AS a 
            ON framing.framing_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON framing.framing_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON framing.created_by = c.id
        GROUP BY framing.framing_id
        ORDER BY framing.created_at DESC";
        // dd($sql);
        $data['AllLaminatorLists'] = DB::select($sql);
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.framing.index', $data);
    }

    public function addFraming()
    {
        $data['menu'] = 'framing';
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
        return view('ProductionLineUp.framing.add', $data);
    }


    public function getQCId(Request $request)
    {
        $batchNo = $request->q;

        $sql = "SELECT 
            ninetydeg.ninetydeg_id
            FROM tbl_factory_ninetydeg_laravel AS ninetydeg
            LEFT JOIN tbl_factory_framing_laravel AS framing
                ON framing.framing_QC = ninetydeg.ninetydeg_id
            WHERE framing.framing_QC IS NULL AND ninetydeg.ninetydeg_batchNo = $batchNo
            ORDER BY ninetydeg.created_at DESC";

        $elqcData = DB::select($sql);;

        if ($elqcData) {
            return response()->json(['elqc_ids' => $elqcData]);
        } else {
            return response()->json(['elqc_ids' => null]);
        }
    }


    public function insert(Request $request){
      //print_r($_POST);exit;

      if ($request->input('err') == '1') {
            return redirect()->back()->with('error', 'Please correct the errors before submitting the form.');
        } else {
            $id = date('YmdHis');
            $data = array(
                'framing_id' => $id,
                'framing_date' => date('d-m-Y'),
                'framing_time' => date('H:i:s'),
                'framing_operator' => $request->input('operator'),
                'framing_source' => 'Bushing',
                'framing_QC' => $request->input('bushingNo'),
                'framing_batchNo' => $request->input('batchNo'),
                'framing_incharge' => $request->input('incharge'),
                //'framing_cycle_no' => $request->input('cycleNo'),
                'framing_shift' => $request->input('shift'),
                'framing_plant' => $request->input('plant'),
                'status' => $request->input('el_type'),
                'framing_pDefectRsn' => $request->input('p_reject_reason'),
                'framing_rfid' => $request->input('rfid'),
                'framing_barcode' => $request->input('barCode'),
                'created_by' => $request->session()->get('empId')
            );


            $res = Framing_Model::create($data);
            
            FramingHistory_Model::create([
                'framing_id' => $id,
                'action' => 'Raised',
                'ip_address' => $this->getUserIP(),
                'created_by' => auth()->id()
            ]);
            $type = $request->input('type', []);
            $size = $request->input('size', []);
            $brand = $request->input('brand', []);
            $qty = $request->input('qty', []);

            if ($request->input('el_type') === '0' && is_array($type) && count($type) > 0) {
                foreach ($type as $i => $dfctType) {
                    // skip empty rows (optional)
                    if ($dfctType === null || $dfctType === '') {
                        continue;
                    }

                    $defectData = array(
                        'framing_Id' => $id,
                        'type' => $dfctType,
                        'size' => $size[$i] ?? null,
                        'brand' => $brand[$i] ?? null,
                        'qty' => $qty[$i] ?? null,
                    );

                    FramingDamage_Model::create($defectData);
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
                    $url = 'production-lineup/framing/add?page=ALL&lock=1&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                    return redirect($url)->with('success', ' Framing data stored successfully!');
                } else {
                    return redirect()->back()->with('success', 'Framing data stored successfully!');
                }
            }
        }
    }
    
}