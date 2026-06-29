<?php

namespace App\Http\Controllers\ProductionLineUp\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalMatrix\{ApprovalStage_Model, Approver_Model};
use App\Models\ProductionLineUp\{PlantTarget_Model, PlantTargetHist_model};

class PlantTarget_Master extends Controller
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
		$data['menu'] = 'plant-target';
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		$data['empId'] = request()->session()->get('empId');
		

		$sql = "SELECT 
			ptl.*,
			asl.stage_title,
			phl.actionBy,
			b.fullname AS actionByName,
			a.fullname AS createdByName
			FROM tbl_factory_plant_target_laravel AS ptl
			INNER JOIN tbl_factory_appr_stage_laravel AS asl 
					ON ptl.stage = asl.id
			LEFT JOIN (
					SELECT phl1.*
					FROM tbl_factory_plant_target_hist_laravel AS phl1
					WHERE phl1.id = (
							SELECT MAX(phl2.id)
							FROM tbl_factory_plant_target_hist_laravel AS phl2
							WHERE phl2.mainTableId = phl1.mainTableId
					)
			) AS phl 
					ON phl.mainTableId = ptl.id
			LEFT JOIN mstr_emp AS b 
					ON phl.actionBy = b.id
			LEFT JOIN mstr_emp AS a 
					ON ptl.created_by = a.id
			ORDER BY ptl.created_at DESC";
			
		$data['AllLists'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1782540643')
			->orderBy('asl.stage_position', 'ASC')
			->get();
			
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
			
			
		$data['PlantMaster'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id',42)
			->get();
			
		$data['batchList'] = DB::table('tbl_factory_production_setup_laravel')
		    ->select('tbl_factory_production_setup_laravel.batchNo')
			->get();

		return view('ProductionLineUp.Master.PlantTarget.index', $data);
	}


	public function insert(Request $request)
	{
		$stages = ApprovalStage_Model::where('stage_module', '1782540643')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->limit(1)->get();
		foreach ($stages as $stage);
		$id = time();
		$data = array(
			'id'            => $id,
			'plantNo'       => request()->input('plant_name'),
			'startDate'     => request()->input('effectFromDate'),
			'endDate'     	=> request()->input('effectToDate'),
			'targetNos'     => request()->input('targetnos'),
			'targetMW'      => request()->input('targetmw'),
			'status'        => '1',
			'stage'         => $stage['id'],
			'created_by'    => request()->session()->get('empId')
		);

		$res = PlantTarget_Model::create($data);
		if ($res->exists) {

			$data = array(
				'id'           => time(),
				'mainTableId'  => $id,
				'remarks'      => 'Raised',
				'action'       => 'Raised',
				'actionBy'     => request()->session()->get('empId'),
				'ip'           => $this->getUserIP(),
			);

			$res = PlantTargetHist_model::create($data);

			return redirect()->to(url('production-lineup/master/plant_target'))->with('success', 'Added Successfully');
		} else {
			return redirect()->to(url('production-lineup/master/plant_target'))->with('failed', 'Something went wrong contact with System Aministrator');
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
