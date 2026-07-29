<?php

namespace App\Http\Controllers\ProductionLineUp\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApprovalMatrix\{ApprovalStage_Model, Approver_Model};

class PlantCapacity_Master extends Controller
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

    public function index(Request $request)
	{
		$data['menu'] = 'production-setup';
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		$data['empId'] = request()->session()->get('empId');
		
		$Cond = [];
		$Condition = 'psml.material = 1';
		
		if(isset($_GET['createdBy']) && $_GET['createdBy'] != ''){
		    $Cond[] = "psl.created_by = '".$_GET['createdBy']."'";
		}
		if(isset($_GET['batchno']) && $_GET['batchno'] != ''){
		    $Cond[] = "psl.batchNo = '".$_GET['batchno']."'";
		}
		if(isset($_GET['plantno']) && $_GET['plantno'] != ''){
		    $Cond[] = "psl.plantNo = '".$_GET['plantno']."'";
		}
		if(isset($_GET['shift']) && $_GET['shift'] != ''){
		    $Cond[] = "psl.fromShift = '".$_GET['shift']."'";
		}
		if(isset($_GET['fromDate']) && $_GET['fromDate'] != ''){
		    $Cond[] = "psl.startDate >= '".$_GET['fromDate']."'";
		}
		if(isset($_GET['toDate']) && $_GET['toDate'] != ''){
		    $Cond[] = "psl.startDate <= '".$_GET['toDate']."'";
		}
		if(count($Cond) > 0){
		  $Condition = $Condition.' AND '.implode(' AND ', $Cond);
		}
		
		$sql = "SELECT 
            psl.*,
            psml.size AS efficiency,
            psml.brand,
            asl.stage_title,
            phl.actionBy,
            b.fullname AS actionByName,
            a.fullname AS createdByName,
            hr_mstr_shift.shift AS ShiftName
        FROM tbl_factory_production_setup_laravel AS psl
        INNER JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        INNER JOIN tbl_factory_appr_stage_laravel AS asl 
            ON psl.stage = asl.id
        LEFT JOIN (
            SELECT phl1.*
            FROM tbl_factory_productsetup_hist_laravel AS phl1
            WHERE phl1.id = (
                SELECT MAX(phl2.id)
                FROM tbl_factory_productsetup_hist_laravel AS phl2
                WHERE phl2.batchNo = phl1.batchNo
            )
        ) AS phl 
            ON phl.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS b 
            ON phl.actionBy = b.id
        LEFT JOIN mstr_emp AS a 
            ON psl.created_by = a.id
        INNER JOIN hr_mstr_shift 
            ON hr_mstr_shift.id = psl.fromShift
        WHERE $Condition
        ORDER BY psl.created_at DESC;";
        
        $data['AllLists'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1752154155')
			->orderBy('asl.stage_position', 'ASC')
			->get();
			
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
			
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
			
		$data['PlantMaster'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id',42)
			->get();
			
		$data['batchList'] = DB::table('tbl_factory_production_setup_laravel')
		    ->select('tbl_factory_production_setup_laravel.batchNo')
			->get();

		return view('ProductionLineUp.ProductionSetUp.production-setup', $data);
	}

    public function getUserIP()
	{
		// Get real visitor IP behind CloudFlare network
		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}

		return $ip;
	}
}
