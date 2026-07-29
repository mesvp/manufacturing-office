<?php

namespace App\Http\Controllers\ProductionLineUp\DLamination;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{NinetyDeg_Model, NinetyDegDamage_Model, NinetyDegHist_Model};

class DLamination_Controller extends Controller
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
        $data['menu'] = 'dlamination';
        $Condition = 'ninetydeg.rwrk_status=1 AND ninetydeg.status=0';
        $sql = "SELECT 
        ninetydeg.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS ninetydeg_operator,
        b.fullname AS ninetydeg_incharge,
        c.fullname AS createdBy,
        (SELECT SUM(d2.cell_qty)
         FROM tbl_factory_ninetydeg_defect_laravel d2
         WHERE d2.ninetydeg_Id = ninetydeg.ninetydeg_id
        ) AS no_of_cell_damage
        FROM tbl_factory_ninetydeg_laravel AS ninetydeg
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
        GROUP BY ninetydeg.ninetydeg_id
        ORDER BY ninetydeg.created_at DESC";
        // dd($sql);
        $data['AllLaminatorLists'] = DB::select($sql);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.DLamination.index', $data);
    }
    
    public function view_delamination($id = null)
    {
        //echo 'hi'; exit;
        $data['menu'] = '';
        $data['laminatorDetails'] = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->leftJoin('mstr_emp as emp1', 'ninetydeg.ninetydeg_operator', '=', 'emp1.id')
            ->leftJoin('mstr_emp as emp2', 'ninetydeg.ninetydeg_incharge', '=', 'emp2.id')
            ->leftJoin('hr_mstr_shift as sh', 'ninetydeg.ninetydeg_shift', '=', 'sh.id')
            ->select('ninetydeg.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
            ->where('ninetydeg.ninetydeg_id', $id)
            ->first();
        $data['defectDetails'] = DB::table('tbl_factory_ninetydeg_defect_laravel as def')
            ->select('def.*', 'emp.fullname as responsible_person')
            ->leftJoin('mstr_emp as emp', 'def.res_prsn', '=', 'emp.id')
            ->where('def.ninetydeg_Id', $id)
            ->get();
        $data['laminatorHistory'] = DB::table('tbl_factory_ninetydeg_history_laravel as history')
            ->select('history.*', 'emp.fullname as created_by')
            ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
            ->where('history.ninetydeg_id', $id)
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
            ->where('mstr_emp.status', '1')
            ->get();
        $batchNo = $data['laminatorDetails']->ninetydeg_batchNo;
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();
            
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.DLamination.view_delamination', $data);
    }
    
    public function update_delamination(Request $request, $id)
    {
        $rwrk_status = $request->input('rwrk_status');
        $rwrk_pg = $request->input('rwrk_pg');
        if($rwrk_pg){
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_ninetydeg_laravel')
                    ->where('ninetydeg_id', $id)
                    ->update(['ninetydeg_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '1']);
                // DB::table('tbl_factory_ninetydeg_defect_laravel')->where('ninetydeg_Id', $id)
                    // ->update(['status' => '1']);
                DB::table('tbl_factory_ninetydeg_history_laravel')->insert([
                    'ninetydeg_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_ninetydeg_laravel')
                    ->where('ninetydeg_id', $id)
                    ->update(['ninetydeg_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '0']);
                DB::table('tbl_factory_ninetydeg_history_laravel')->insert([
                    'ninetydeg_id' => $id,
                    'action' => 'QC Damage',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
                $cell_positions = $request->input('cell_position', []);
                $cell_qtys = $request->input('cell_qty', []);
                $dmgMat_reasons = $request->input('dmgMat_reason', []);
                $defect_categories = $request->input('dmgMat_cat', []);
                $res_prsns = $request->input('res_prsn', []);
                $res_machines = $request->input('res_machine', []);
                $replace_datas = $request->input('replace_data', []);
                
                $def_ids = $request->input('def_id', []);

                if (!empty($def_ids)) {
                    foreach ($def_ids as $i => $def_id) {
                        $replace = $replace_datas[$i] ?? 'NO';
                        NinetyDegDamage_Model::where('def_id', $def_id)
                            ->update([
                                'replace' => $replace
                            ]);
                    }
                }
                
                if (is_array($cell_positions) && count($cell_positions) > 0) {
                    foreach ($cell_positions as $i => $cell_no) {
                        // skip empty rows (optional)
                        if ($cell_no === null || $cell_no === '') {
                            continue;
                        }
    
                        $defectData = array(
                            'ninetydeg_Id' => $id,
                            'cell_no' => $cell_no,
                            'cell_qty' => $cell_qtys[$i] ?? null,
                            'defectRsn' => $dmgMat_reasons[$i] ?? null,
                            'defectCatgry' => $defect_categories[$i] ?? null,
                            'res_prsn' => $res_prsns[$i] ?? null,
                            'res_machine' => $res_machines[$i] ?? null
                        );
    
                        NinetyDegDamage_Model::create($defectData);
                    }
                }
            }
        } else {
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_ninetydeg_laravel')
                    ->where('ninetydeg_id', $id)
                    ->update(['ninetydeg_source' => 'ReWork', 'rwrk_status' => '', 'status' => '0']);
                // DB::table('tbl_factory_ninetydeg_defect_laravel')->where('ninetydeg_Id', $id)
                    // ->update(['status' => '1']);
                DB::table('tbl_factory_ninetydeg_history_laravel')->insert([
                    'ninetydeg_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
                $cell_positions = $request->input('cell_position', []);
                $cell_qtys = $request->input('cell_qty', []);
                $dmgMat_reasons = $request->input('dmgMat_reason', []);
                $defect_categories = $request->input('dmgMat_cat', []);
                $res_prsns = $request->input('res_prsn', []);
                $res_machines = $request->input('res_machine', []);
                $replace_datas = $request->input('replace_data', []);
                $def_ids = $request->input('def_id', []);

                if (!empty($def_ids)) {
                    foreach ($def_ids as $i => $def_id) {
                        $replace = $replace_datas[$i] ?? 'NO';
                        NinetyDegDamage_Model::where('def_id', $def_id)
                            ->update([
                                'replace' => $replace
                            ]);
                    }
                }
                
                if (is_array($cell_positions) && count($cell_positions) > 0) {
                    foreach ($cell_positions as $i => $cell_no) {
                        // skip empty rows (optional)
                        if ($cell_no === null || $cell_no === '') {
                            continue;
                        }
    
                        $defectData = array(
                            'ninetydeg_Id' => $id,
                            'cell_no' => $cell_no,
                            'cell_qty' => $cell_qtys[$i] ?? null,
                            'defectRsn' => $dmgMat_reasons[$i] ?? null,
                            'defectCatgry' => $defect_categories[$i] ?? null,
                            'res_prsn' => $res_prsns[$i] ?? null,
                            'res_machine' => $res_machines[$i] ?? null,
                            'source' => 'Delamination',
                        );
    
                        NinetyDegDamage_Model::create($defectData);
                    }
                }
            } else {
                DB::table('tbl_factory_ninetydeg_laravel')
                    ->where('ninetydeg_id', $id)
                    ->update(['ninetydeg_source' => 'ReWork', 'rwrk_status' => '2', 'status' => '2']);
                DB::table('tbl_factory_ninetydeg_history_laravel')->insert([
                    'ninetydeg_Id' => $id,
                    'action' => 'Reject',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        if($rwrk_pg){
        return redirect('production-lineup/90deg-qc')->with('success', '90deg QC rework status updated successfully!');
        } else {
        return redirect('production-lineup/dlamination')->with('success', '90deg QC rework status updated successfully!');
        }
    }
    
    public function damage_report()
    {
        $data['menu'] = 'dlamination-damage-report';

        $sql = "SELECT 
        ninetydeg.*,
        psl.wattage,
        sh.shift AS shiftdtl,
        a.fullname AS ninetydeg_operator,
        b.fullname AS ninetydeg_incharge,
        c.fullname AS createdBy,
        (SELECT SUM(d2.cell_qty)
         FROM tbl_factory_ninetydeg_defect_laravel d2
         WHERE d2.ninetydeg_Id = ninetydeg.ninetydeg_id
        ) AS no_of_cell_damage
        FROM tbl_factory_ninetydeg_laravel AS ninetydeg
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
        GROUP BY ninetydeg.ninetydeg_id
        ORDER BY ninetydeg.created_at DESC";

        // dd($sql);
        $data['AllDamageLists'] = DB::select($sql);
        //$data['menu'] = 'trimming-damage';
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.DLamination.damage', $data);
    }
}
