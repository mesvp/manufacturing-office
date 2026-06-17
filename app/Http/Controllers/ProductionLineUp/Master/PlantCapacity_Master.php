<?php

namespace App\Http\Controllers\ProductionLineUp\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


	public function Approval_list(Request $request)
	{
		$data['menu'] = 'production-setup-approval';
		$data['empId'] = request()->session()->get('empId');
		$data['admindata'] = Admin::all_admin();

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
		c.fullname AS createdByName,
		hr_mstr_shift.shift AS ShiftName
		FROM tbl_factory_production_setup_laravel as psl
		INNER JOIN tbl_factory_production_setup_material_laravel as psml 
			ON psml.batchNo = psl.batchNo
		INNER JOIN tbl_factory_appr_stage_laravel as asl 
			ON psl.stage = asl.id
		INNER JOIN hr_mstr_shift 
			ON hr_mstr_shift.id = psl.fromShift
		LEFT JOIN (
			SELECT phl1.*
			FROM tbl_factory_productsetup_hist_laravel as phl1
			WHERE phl1.id = (
				SELECT MAX(phl2.id)
				FROM tbl_factory_productsetup_hist_laravel as phl2
				WHERE phl2.batchNo = phl1.batchNo
			)
		) as phl 
			ON phl.batchNo = psl.batchNo
		LEFT JOIN mstr_emp as b 
			ON phl.actionBy = b.id
		LEFT JOIN mstr_emp as c 
			ON psl.created_by = c.id
		WHERE psml.material = 1
		ORDER BY psl.created_at DESC";
        
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
			
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.ProductionSetUp.production-setup-approval-list', $data);
	}

	public function add(Request $request)
	{
		$data['menu'] = 'production-setup';
		$MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
		$FinishedGood = [];
		$i = 0;
		foreach ($MAT_DATA as $Val) {
			if (isset($Val->Raw_Material)) {
				//$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
				$Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
					->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
					->where('materialmanagement_add_material.id', $Val->Raw_Material)
					->first();

				$FinishedGood[$i]['matId'] = $Val->Raw_Material;
				$FinishedGood[$i]['matName'] = $Val->RawMaterial->matname;
				$i++;
			}
		}
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
		$data['PlantMaster'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id',42)
			->get();
		$data['ProcessStage'] = DB::table('tbl_process_stage')
			->select('tbl_process_stage.*')
      ->orderBy('tbl_process_stage.stage_pos', 'ASC')
			->get();
		$data['FinishedGood'] = $FinishedGood;
		$data['MaterialList'] = materialMaster_Model::all();
		
      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.ProductionSetUp.add-production', $data);
	}


	public function viewDetails(Request $request)
	{
		if (request()->session()->has('empId')) {
			$batchNo = request()->value;
			$data['productSetDtls'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.*',
					'psml.size AS efficiency',
					'psml.brand',
					'asl.stage_title',
					'b.fullname AS actionByName',
				    'hr_mstr_shift.shift AS ShiftName'
				)
				->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
				->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
				->leftJoinSub(
					DB::table('tbl_factory_productsetup_hist_laravel as phl1')
						->select('phl1.*')
						->whereRaw('phl1.id = (
            SELECT MAX(phl2.id)
            FROM tbl_factory_productsetup_hist_laravel as phl2
            WHERE phl2.batchNo = phl1.batchNo
          )'),
					'phl',
					'phl.batchNo',
					'=',
					'psl.batchNo'
				)
				->leftJoin('mstr_emp as b', 'phl.actionBy', '=', 'b.id')
			->Join('hr_mstr_shift', 'hr_mstr_shift.id', '=', 'psl.fromShift')
				->orderBy('psl.created_at', 'DESC')
				->where('psml.material', 1)
				->where('psl.batchNo', $batchNo)
				->get();
			$data['productSetMtrl'] = DB::table('tbl_factory_production_setup_material_laravel as psml')
				->select(
					'psml.*','mml.title','mml.uom as mat_uom'
				)
				->join('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
				->orderBy('psml.created_at', 'DESC')
				->where('psml.batchNo', $batchNo)
				->get();
			$data['productSetTrail'] = DB::table('tbl_factory_productsetup_hist_laravel')
				->select('tbl_factory_productsetup_hist_laravel.*', 'b.fullname')
				->join('mstr_emp AS b', 'tbl_factory_productsetup_hist_laravel.actionBy', '=', 'b.id')
				->where('tbl_factory_productsetup_hist_laravel.batchNo', $batchNo)
				->get();
				
				
			    $MAT_DATA = ProductCategories_Add_Product::where('Approve_status', 'APPROVE')->get();
        		$FinishedGood = [];
        		$i = 0;
        		foreach ($MAT_DATA as $Val) {
        			if (isset($Val->Raw_Material)) {
        				//$Val->RawMaterial = MaterialManagement_Add_Material::find($Val->Raw_Material);
        				$Val->RawMaterial = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
        					->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
        					->where('materialmanagement_add_material.id', $Val->Raw_Material)
        					->first();
        
        				$FinishedGood[$i]['matId'] = $Val->Raw_Material;
        				$FinishedGood[$i]['matName'] = $Val->RawMaterial->matname;
        				$i++;
        			}
        		}
		
		    $data['FinishedGood'] = $FinishedGood;
			$data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'production-setup';

      $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.ProductionSetUp.production-setup-view', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}


	public function insert(Request $request)
	{
		//print_r($_POST);
		//exit;


		$stages = ApprovalStage_Model::where('stage_module', '1752154155')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->limit(1)->get();
		foreach ($stages as $stage);
		$id = time();
		$data = array(
			'id'            => $id,
			'batchNo'       => request()->input('batchNo'),
			'plantNo'       => request()->input('plant_no'),
			'startDate'     => request()->input('start_date'),
			'fromShift'     => request()->input('shift'),
			'wattage'       => request()->input('wattage'),
			'finishGood'    => request()->input('finished_good'),
			'cellRow'       => request()->input('rowNo'),
			'celColumn'     => request()->input('colNo'),
			'comment'       => request()->input('comment'),
			'status'        => '0',
			'stage'         => $stage['id'],
			'created_by'    => request()->session()->get('empId')
		);

		$res = ProductSetUp_Model::create($data);
		if ($res->exists) {


			$material  = request()->input('material');
			$size = request()->input('size');
			$brand = request()->input('brand');
			$qty = request()->input('qty');
			$uom = request()->input('uom');
			$bomMat = request()->input('bom_material');
			$bomQty = request()->input('bom_qty');
			$useStage = request()->input('useStage');

			foreach ($material as $key => $value) {
				$data = array(
					'batchNo'      => request()->input('batchNo'),
					'material'     => $material[$key],
					'size'         => $size[$key],
					'brand'        => $brand[$key],
					'qty'          => $qty[$key],
					'uom'          => $uom[$key],
					'bomMat'          => $bomMat[$key],
					'bomQty'          => $bomQty[$key],
					'useStage'          => $useStage[$key]
				);

				ProductSetUpMaterial_Model::create($data);
			}


			$data = array(
				'id'           => time(),
				'batchNo'      => request()->input('batchNo'),
				'remarks'      => request()->input('comment'),
				'action'       => 'Raised',
				'actionBy'     => request()->session()->get('empId'),
				'ip'           => $this->getUserIP(),
			);

			$res = ProductionSetUpHist_Model::create($data);

			return redirect()->to(url('production-lineup/production-setup'))->with('success', 'Schedule added Successfully');
		} else {
			return redirect()->to(url('production-lineup/production-setup'))->with('failed', 'Something went wrong contact with System Aministrator');
		}
	}


	public function approvalAction(Request $request)
	{
		if (request()->session()->has('empId')) {
			$remarks = request()->input('remark');
			$submitData = request()->input('submitData');
			$submitData = explode('_', $submitData);
			$batchNo = $submitData[0];
			$current_Stage = $submitData[1];

			if (request()->input('ApprStat') == 1) {

				$ActionString = $submitData[2] . " Approved";

				//$nextPositionIdObj = [];
				$nextPositionIdObj = ApprovalStage_Model::where('id', '>', $current_Stage)
					->where('stage_module', '1752154155')
					->where('stage_stat', '1')
					->orderBy('id', 'asc')
					->limit(1)
					->get();

				foreach ($nextPositionIdObj as $nextPosition);

				if (count($nextPositionIdObj) > 0) {
					$nextStage = $nextPositionIdObj[0]['id'];

					$result = ProductSetUp_Model::where('batchNo', $batchNo);
					$input['stage'] = $nextStage;
					$input['status'] = 0;
					$input['appr_process'] = 1;
					$res = $result->update($input);
				} else {
					$result = ProductSetUp_Model::where('batchNo', $batchNo);
					$input['status'] = 1;
					$input['appr_process'] = 1;
					$res = $result->update($input);
				}
			} else {
				if (request()->input('ApprStat') == 4) {
					$ActionString = "Rejected";
				} else if (request()->input('ApprStat') == 3) {
					$ActionString = "Hold";
				} else if (request()->input('ApprStat') == 2) {
					$ActionString = "Recheck";
				}

				$result = ProductSetUp_Model::where('batchNo', $batchNo);
				$input['status'] = request()->input('ApprStat');
				$input['appr_process'] = 1;
				$res = $result->update($input);
			}

			if ($res == 1) {
				$data = array(
					'id'              => time(),
					'batchNo'         => $batchNo,
					'remarks'         => $remarks,
					'action'          => $ActionString,
					'actionBy'        => request()->session()->get('empId'),
					'ip'              => $this->getUserIP(),
				);

				$res = ProductionSetUpHist_Model::create($data);

				return redirect()->to(url('production-lineup/production-setup/approval-list'))->with('success', 'aprroval Action done Successfully');
			} else {
				return redirect()->to(url('production-lineup/production-setup/approval-list'))->with('failed', 'Something went wrong contact with System Aministrator');
			}
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}
}
