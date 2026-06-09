<?php

namespace App\Http\Controllers\ProductionLineUp\StringerOP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\ProductionLineUp\Factory_Stringer_Op;
use App\Models\ProductionLineUp\Factory_Stringer_Op_Material;
use App\Models\ProductionLineUp\Factory_Stringer_Op_History;
use App\Models\ApprovalMatrix\ApprovalStage_Model;

class StringerOP_Controller extends Controller
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

	public function getstringerOPMaterial(Request $request)
	{
		$batchNo = $request->query('q');

		$batchData =  DB::table('tbl_factory_production_setup_laravel as psl')
			->select(
				'psl.*',
				'psml.size AS efficiency',
				'psml2.size AS StringSize',
				'psml.brand AS cellBrand',
				'psml2.brand',
				'asl.stage_title',
				'phl.actionBy',
				'b.fullname AS actionByName',
				'mml.title',
				'prj_material.material_name as matname'
			)
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml2', 'psml2.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
			->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
			->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
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
			->join('tbl_factory_material_master_laravel as mml', 'psml2.material', '=', 'mml.id')
			->orderBy('psl.created_at', 'DESC')
			->where('psl.batchNo', $batchNo)
			->where('psml.material', 1)
			->where('psml2.material', 2)
			->get();
		foreach ($batchData as $batch)

			$materials[] = [
				'id' => 2 ?? 'N/A',
				'name' => $batch->title ?? 'N/A',
				'size' => $batch->StringSize ?? 'N/A',
				'brand' => $batch->brand ?? 'N/A'
			];

		return response()->json([
			'wattage' => $batch->wattage,
			'efficiency' => $batch->efficiency,
			'StringSize' => $batch->StringSize,
			'cell_company' => $batch->cellBrand,
			'finishGood' => $batch->matname ?? '',
			'brand' => $batch->brand,
			'plantNo' => $batch->plantNo,
			"materials"   => $materials
		]);
	}

	public function index(Request $request)
	{
		$data['menu'] = 'stringer-op';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'prdm.material = 2';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "psl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "psl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "psl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "psl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "psl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "psl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}

		$sql = "SELECT 
            psl.*,
            prd.wattage,
            asl.stage_title,
            asl.stage_position,
            prdm.size,
            prdm.brand,
            sh.shift AS shiftdtl,
            SUM(psml.productionQty) AS totalProductionQty,
            SUM(psml.rejectQty) AS totalRejectQty,
            a.fullname AS addByName,
            b.fullname AS actionByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName
            FROM tbl_factory_stringer_op_laravel AS psl
            INNER JOIN tbl_factory_stringer_op_material_laravel AS psml 
                ON psml.StringerOpId = psl.id
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON psl.stage = asl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = psl.shift
            LEFT JOIN tbl_factory_production_setup_laravel AS prd 
                ON prd.batchNo = psl.batchNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS prdm 
                ON prdm.batchNo = psl.batchNo
            LEFT JOIN (
                SELECT phl1.*
                FROM tbl_factory_stringer_op_history_laravel AS phl1
                WHERE phl1.id = (
                    SELECT MAX(phl2.id)
                    FROM tbl_factory_stringer_op_history_laravel AS phl2
                    WHERE phl2.stropId = phl1.stropId
                )
            ) AS phl 
                ON phl.stropId = psl.id
            LEFT JOIN mstr_emp AS a 
                ON psl.created_by = a.id
            LEFT JOIN mstr_emp AS b 
                ON phl.actionBy = b.id
            LEFT JOIN mstr_emp AS c 
                ON psl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON psl.checker = d.id
            WHERE $Condition
            GROUP BY psml.StringerOpId
            ORDER BY psml.created_at DESC;
        ";

		$data['AllLists'] = DB::select($sql);

		/*
		$data['AllLists'] = DB::table('tbl_factory_stringer_op_laravel as psl')
			->select(
				'psl.*',
				'prd.wattage',
				'asl.stage_title',
				'asl.stage_position',
				'prdm.size',
				'prdm.brand',
				'sh.shift as shiftdtl',
				DB::raw('SUM(psml.productionQty) as totalProductionQty'),
				DB::raw('SUM(psml.rejectQty) as totalRejectQty'),
				'a.fullname AS addByName',
				'b.fullname AS actionByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName'
			)
			->join('tbl_factory_stringer_op_material_laravel as psml', 'psml.StringerOpId', '=', 'psl.id')
			->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
			->leftjoin('hr_mstr_shift as sh', 'sh.id', '=', 'psl.shift')
			->leftjoin('tbl_factory_production_setup_laravel as prd', 'prd.batchNo', '=', 'psl.batchNo')
			->leftjoin('tbl_factory_production_setup_material_laravel as prdm', 'prdm.batchNo', '=', 'psl.batchNo')
			->leftJoinSub(
				DB::table('tbl_factory_stringer_op_history_laravel as phl1')
					->select('phl1.*')
					->whereRaw('phl1.id = (
            SELECT MAX(phl2.id)
            FROM tbl_factory_stringer_op_history_laravel as phl2
            WHERE phl2.stropId = phl1.stropId
          )'),
				'phl',
				'phl.stropId',
				'=',
				'psl.id'
			)
			->leftJoin('mstr_emp as a', 'psl.created_by', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'phl.actionBy', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'psl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'psl.checker', '=', 'd.id')
			->where('prdm.material', 2)
			->orderBy('psml.created_at', 'DESC')
			->groupBy('psml.StringerOpId')
			->get();
		*/
		// 			dd($data['AllLists']);
		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753348533')
			->orderBy('asl.stage_position', 'ASC')
			->get();


		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.StringerOP.stringer-op-request', $data);
	}

	public function Approval_list(Request $request)
	{
		$data['menu'] = 'stringer-op-approval-list';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'prdm.material = 2';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "psl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "psl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "psl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "psl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "psl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "psl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}

		$sql = "SELECT 
            psl.*,
            prd.wattage,
            asl.stage_title,
            asl.stage_position,
            prdm.size,
            prdm.brand,
            sh.shift AS shiftdtl,
			phl.actionBy,
            SUM(psml.productionQty) AS totalProductionQty,
            SUM(psml.rejectQty) AS totalRejectQty,
            a.fullname AS addByName,
            b.fullname AS actionByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName
            FROM tbl_factory_stringer_op_laravel AS psl
            INNER JOIN tbl_factory_stringer_op_material_laravel AS psml 
                ON psml.StringerOpId = psl.id
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON psl.stage = asl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = psl.shift
            LEFT JOIN tbl_factory_production_setup_laravel AS prd 
                ON prd.batchNo = psl.batchNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS prdm 
                ON prdm.batchNo = psl.batchNo
            LEFT JOIN (
                SELECT phl1.*
                FROM tbl_factory_stringer_op_history_laravel AS phl1
                WHERE phl1.id = (
                    SELECT MAX(phl2.id)
                    FROM tbl_factory_stringer_op_history_laravel AS phl2
                    WHERE phl2.stropId = phl1.stropId
                )
            ) AS phl 
                ON phl.stropId = psl.id
            LEFT JOIN mstr_emp AS a 
                ON psl.created_by = a.id
            LEFT JOIN mstr_emp AS b 
                ON phl.actionBy = b.id
            LEFT JOIN mstr_emp AS c 
                ON psl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON psl.checker = d.id
            WHERE $Condition
            GROUP BY psml.StringerOpId
            ORDER BY psml.created_at DESC;
        ";

		$data['AllLists'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753348533')
			->orderBy('asl.stage_position', 'ASC')
			->get();

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.StringerOP.stringer-op-approval-list', $data);
	}

	public function Detailed_list(Request $request)
	{
		$data['menu'] = 'stringer-op-detailed';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'psl.status = 1 AND prdm.material = 2';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "psl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "psl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "psl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "psl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "psl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "psl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}
		$sql = "
		SELECT 
    psl.*,
    psml.*,
    prd.wattage,
    prd.plantNo,
    asl.stage_title,
    prdm.size,
    prdm.brand,
    sh.shift as shiftdtl,
    psml.productionQty as totalProductionQty,
    psml.rejectQty as totalRejectQty,
    phl.actionBy,
    a.fullname AS addByName,
    b.fullname AS actionByName,
    c.fullname AS operatorName,
    d.fullname AS checkerName,
    prj_material.material_name as matname
FROM tbl_factory_stringer_op_laravel as psl
INNER JOIN tbl_factory_stringer_op_material_laravel as psml 
    ON psml.stringerOpId = psl.id
INNER JOIN tbl_factory_appr_stage_laravel as asl 
    ON psl.stage = asl.id
INNER JOIN hr_mstr_shift as sh 
    ON sh.id = psl.shift
INNER JOIN tbl_factory_production_setup_laravel as prd 
    ON prd.batchNo = psl.batchNo
INNER JOIN tbl_factory_production_setup_material_laravel as prdm 
    ON prdm.batchNo = psl.batchNo
INNER JOIN mstr_emp as a 
    ON psl.created_by = a.id
INNER JOIN mstr_emp as c 
    ON psl.operator = c.id
INNER JOIN mstr_emp as d 
    ON psl.checker = d.id
INNER JOIN materialmanagement_add_material 
    ON materialmanagement_add_material.id = prd.finishGood
INNER JOIN prj_material 
    ON materialmanagement_add_material.Material_Name = prj_material.id
LEFT JOIN (
    SELECT phl1.*
    FROM tbl_factory_stringer_op_history_laravel as phl1
    WHERE phl1.id = (
        SELECT MAX(phl2.id)
        FROM tbl_factory_stringer_op_history_laravel as phl2
        WHERE phl2.stropId = phl1.stropId
    )
) as phl 
    ON phl.stropId = psl.id
LEFT JOIN mstr_emp as b 
    ON phl.actionBy = b.id
WHERE $Condition
ORDER BY psl.created_at DESC;
		";
		$data['AllLists'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753348533')
			->orderBy('asl.stage_position', 'ASC')
			->get();

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.StringerOP.stringer-op-detailed', $data);
	}

	public function add_stringer_op(Request $request)
	{
		$data['menu'] = 'stringer-op';
		$data['employees'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
		$data['batchList'] = DB::table('tbl_factory_production_setup_laravel as psl')
			->select(
				'psl.*',
				'psml.size AS efficiency',
				'psml.brand',
				'asl.stage_title',
				'phl.actionBy',
				'b.fullname AS actionByName'
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
			->orderBy('psl.created_at', 'DESC')
			->where('psml.material', 2)
			->where('psl.status', 1)
			->get();
		$data['PlantMaster'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id', 42)
			->get();
		$data['DmgRsn'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id', 44)
			->get();
		$data['DefectCat'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id', 43)
			->get();
		$data['StringerNo'] = DB::table('master_type_dtls')
			->select('master_type_dtls.*')
			->where('master_type_dtls.parent_id', 45)
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.StringerOP.add-stringer-op', $data);
	}

	public function view_stringer_op(Request $request, $id)
	{

		$data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'stringer-op';
		$data['id'] = $id;
		if ($id) {
			$data['stringerOpDetails'] = DB::table('tbl_factory_stringer_op_laravel as psl')
				->select(
					'psl.*',
					'sh.shift as shiftdtl',
					'a.fullname AS addByName',
					'c.fullname AS operatorName',
					'd.fullname AS checkerName'
				)
				->leftJoin('mstr_emp as a', 'psl.created_by', '=', 'a.id')
				->leftJoin('mstr_emp as c', 'psl.operator', '=', 'c.id')
				->leftJoin('mstr_emp as d', 'psl.checker', '=', 'd.id')
				->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'psl.shift')
				->where('psl.id', $id)
				->first();
			$batchNo = $data['stringerOpDetails']->batchNo;
			$data['stringerOpMaterials'] = Factory_Stringer_Op_Material::where('stringerOpId', $id)->get();
			$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.plantNo',
					'psl.wattage',
					'psml.size AS efficiency',
					'psml2.size AS StringSize',
					'psml.brand AS cellBrand',
					'psml2.brand AS StringBrand',
					'prj_material.material_name as matname'
				)
				->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
				->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
				->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($batchNo) {
					$join->on('psml.batchNo', '=', 'psl.batchNo')
						->where('psml.material', '=', 1);
				})
				->join('tbl_factory_production_setup_material_laravel as psml2', function ($join) use ($batchNo) {
					$join->on('psml2.batchNo', '=', 'psl.batchNo')
						->where('psml2.material', '=', 2);
				})
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
				->orderBy('psl.created_at', 'DESC')
				->where('psl.batchNo', $data['stringerOpDetails']->batchNo)
				->get();
			$data['stringerOpHistory'] = DB::table('tbl_factory_stringer_op_history_laravel AS f')
				->leftJoin('mstr_emp AS a', 'f.actionBy', '=', 'a.id')
				->select('f.*', 'a.fullname AS actionBy')
				->where('f.stropId', $id)
				->get();
			// dd($data['stringerOpHistory']);
		} else {
			$data['stringerOpDetails'] = null;
			$data['stringerOpMaterials'] = [];
		}
		
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.StringerOP.stringer-op-view-request', $data);
	}

	public function approveDtls(Request $request)
	{
		$data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'stringer-op';
		if (request()->session()->has('empId')) {
			$batchNo = request()->value;
			if ($batchNo) {
				$data['stringerOpDetails'] = DB::table('tbl_factory_stringer_op_laravel as psl')
					->select(
						'psl.*',
						'sh.shift as shiftdtl',
						'a.fullname AS addByName',
						'c.fullname AS operatorName',
						'd.fullname AS checkerName'
					)
					->leftJoin('mstr_emp as a', 'psl.created_by', '=', 'a.id')
					->leftJoin('mstr_emp as c', 'psl.operator', '=', 'c.id')
					->leftJoin('mstr_emp as d', 'psl.checker', '=', 'd.id')
					->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'psl.shift')
					->where('psl.id', $batchNo)
					->first();
				$data['stringerOpMaterials'] = Factory_Stringer_Op_Material::where('stringerOpId', $batchNo)->get();
				$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
					->select(
						'psl.wattage',
						'psml.size AS efficiency',
						'psml2.size AS StringSize',
						'psml.brand AS cellBrand',
						'psml2.brand AS StringBrand',
						'prj_material.material_name as matname'
					)
					->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
					->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
					->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($batchNo) {
						$join->on('psml.batchNo', '=', 'psl.batchNo')
							->where('psml.material', '=', 1);
					})
					->join('tbl_factory_production_setup_material_laravel as psml2', function ($join) use ($batchNo) {
						$join->on('psml2.batchNo', '=', 'psl.batchNo')
							->where('psml2.material', '=', 2);
					})
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
					->orderBy('psl.created_at', 'DESC')
					->where('psl.batchNo', $data['stringerOpDetails']->batchNo)
					->get();
				$data['stringerOpHistory'] = DB::table('tbl_factory_stringer_op_history_laravel AS f')
					->leftJoin('mstr_emp AS a', 'f.actionBy', '=', 'a.id')
					->select('f.*', 'a.fullname AS actionBy')
					->where('f.stropId', $batchNo)
					->get();
			} else {
				$data['stringerOpDetails'] = null;
				$data['stringerOpMaterials'] = [];
			}

			$data['productSetDtls'] = DB::table('tbl_factory_stringer_op_laravel as psl')
				->select(
					'psl.*',
					'asl.stage_title',
					'b.fullname AS actionByName'
				)
				->join('tbl_factory_stringer_op_material_laravel as psml', 'psml.stringerOpId', '=', 'psl.id')
				->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
				->leftJoinSub(
					DB::table('tbl_factory_stringer_op_history_laravel as phl1')
						->select('phl1.*')
						->whereRaw('phl1.id = (
            SELECT MAX(phl2.id)
            FROM tbl_factory_stringer_op_history_laravel as phl2
            WHERE phl2.stropId = phl1.stropId
          )'),
					'phl',
					'phl.stropId',
					'=',
					'psl.id'
				)
				->leftJoin('mstr_emp as b', 'phl.actionBy', '=', 'b.id')
				->orderBy('psl.created_at', 'DESC')
				->where('psl.id', $batchNo)
				->get();
			$data['productSetTrail'] = DB::table('tbl_factory_stringer_op_history_laravel')
				->select('tbl_factory_stringer_op_history_laravel.*', 'b.fullname')
				->join('mstr_emp AS b', 'tbl_factory_stringer_op_history_laravel.actionBy', '=', 'b.id')
				->where('tbl_factory_stringer_op_history_laravel.stropId', $batchNo)
				->get();
				
			$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.StringerOP.approve-details', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
		}
	}

	public function store_stringer_op(Request $request)
	{
		// dd($request->all());
		// $request->validate([
		// 	'date' => 'required',
		// 	'shift' => 'required',
		// 	'production_qty' => 'required',
		// 	'rejection_qty' => 'required',
		// 	'material' => 'required',
		// 	'reason' => 'required',
		// 	'time' => 'required',
		// 	'defectCat' => 'required',
		// 	'wattage' => 'nullable',
		// 	'efficiency' => 'nullable'
		// ]);

		$stages = ApprovalStage_Model::where('stage_module', '1753348533')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->limit(1)
			->get();

		foreach ($stages as $stage);

		$id = time();
		$data = [
			'id' => $id,
			'batchNo' => $request->batch_no ?? '',
			'date' => $request->date,
			'shift' => $request->shift ?? '',
			'plant' => $request->plant_no ?? '',
			'strNo' => $request->strNo ?? null,
			'operator' => $request->operator ?? null,
			'checker' => $request->checker ?? null,
			'created_by' => auth()->id(),
			'status' => 0,
			'stage' => $stage->id ?? null,
			'created_at' => now(),
			'updated_at' => now(),
		];
		$res = Factory_Stringer_Op::create($data);

		if ($res->exists) {
			$materials  = request()->input('materials');
			// dd($materials);
			foreach ($materials as $key => $value) {
				// echo $request->material[$key];exit;
				$materialData = [
					'batchNo' => $request->batch_no,
					'stringerOpId' => $id,
					'material' => $value['name'],
					'stringerNo' => $value['stringer_no'],
					'time' => $value['time'],
					'productionQty' => $value['production_qty'],
					'RejectQty' => $value['rejection_qty'],
					'reason' => $value['stage'],
					'defectCat' => $value['defect_category'],
				];
				Factory_Stringer_Op_Material::create($materialData);
			}

			$logData = [
				'id' => time(),
				'stropId' => $id,
				'remarks' => 'Request Raised',
				'action' => 'Raised',
				'actionBy' => auth()->id(),
				'ip' => $this->getUserIP(),
			];

			Factory_Stringer_Op_History::create($logData);

			return redirect('production-lineup/stringer-op')->with('success', 'Stringer OP Request Created Successfully');
		} else {
			return redirect('production-lineup/stringer-op')->with('failed', 'Something went wrong, please contact the System Administrator');
		}
	}


	public function approvalAction(Request $request)
	{
		if (request()->session()->has('empId')) {
			// request()->value;
			$remarks = request()->input('remark');
			$submitData = request()->input('submitData');
			$submitData = explode('_', $submitData);
			$batchNo = $submitData[0];
			$current_Stage = $submitData[1];

			if (request()->input('ApprStat') == 1) {

				$ActionString = $submitData[2] . " Approved";

				//$nextPositionIdObj = [];
				$nextPositionIdObj = ApprovalStage_Model::where('id', '>', $current_Stage)
					->where('stage_module', '1753348533')
					->where('stage_stat', '1')
					->orderBy('id', 'asc')
					->limit(1)
					->get();

				foreach ($nextPositionIdObj as $nextPosition);

				if (count($nextPositionIdObj) > 0) {
					$nextStage = $nextPositionIdObj[0]['id'];

					$result = Factory_Stringer_Op::where('id', $batchNo);
					$input['stage'] = $nextStage;
					$input['status'] = 0;
					$input['appr_process'] = 1;
					$res = $result->update($input);
				} else {
					$result = Factory_Stringer_Op::where('id', $batchNo);
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

				$result = Factory_Stringer_Op::where('id', $batchNo);
				$input['status'] = request()->input('ApprStat');
				$input['appr_process'] = 1;
				$res = $result->update($input);
			}

			if ($res == 1) {
				$data = array(
					'id'              => time(),
					'stropId'         => $batchNo,
					'remarks'         => $remarks,
					'action'          => $ActionString,
					'actionBy'        => request()->session()->get('empId'),
					'ip'              => $this->getUserIP(),
				);

				$res = Factory_Stringer_Op_History::create($data);

				return redirect()->to(url('production-lineup/stringer-op-approval-list'))->with('success', 'Approval Action done Successfully');
			} else {
				return redirect()->to(url('production-lineup/stringer-op-approval-list'))->with('failed', 'Something went wrong contact with System Aministrator');
			}
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}

	public function formUpdateView($id)
	{
		if (request()->session()->has('empId')) {
			$data['menu'] = 'stringer-op';
			$data['employees'] = DB::table('mstr_emp')
				->select('mstr_emp.*')
				->get();
			$data['ShiftMaster'] = DB::table('hr_mstr_shift')
				->select('hr_mstr_shift.*')
				->get();

			$data['materialMaster'] = DB::table('tbl_factory_material_master_laravel')
				->select('id', 'title')
				->where('id', 2)
				->get();

			// Modified query to get materials correctly for Stringer OP
			$data['stringerOpMaterials'] = DB::table('tbl_factory_stringer_op_material_laravel as psml')
				->select(
					'psml.id',
					'psml.material as mat_id',
					'psml.stringerNo',
					'psml.time',
					'psml.productionQty',
					'psml.RejectQty',
					'psml.reason',
					'psml.defectCat',
					'mml.title as material_name'
				)
				->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
				->where('psml.stringerOpId', $id)
				->get();

			$data['PlantMaster'] = DB::table('master_type_dtls')
				->select('master_type_dtls.*')
				->where('master_type_dtls.parent_id', 42)
				->get();
			$data['DmgRsn'] = DB::table('master_type_dtls')
				->select('master_type_dtls.*')
				->where('master_type_dtls.parent_id', 44)
				->get();
			$data['DefectCat'] = DB::table('master_type_dtls')
				->select('master_type_dtls.*')
				->where('master_type_dtls.parent_id', 43)
				->get();
			$data['StringerNo'] = DB::table('master_type_dtls')
				->select('master_type_dtls.*')
				->where('master_type_dtls.parent_id', 45)
				->get();

			$data['stringerOpDetails'] = Factory_Stringer_Op::find($id);

			$data['batchList'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.*',
					'psml.size AS efficiency',
					'psml.brand',
					'asl.stage_title',
					'phl.actionBy',
					'b.fullname AS actionByName'
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
				->orderBy('psl.created_at', 'DESC')
				->where('psml.material', 2)
				->where('psl.status', 1)
				->get();

			$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.*',
					'psml.size AS efficiency',
					'psml2.size AS StringSize',
					'psml.brand AS cellBrand',
					'psml2.brand',
					'prj_material.material_name as matname'
				)
				->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
				->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
				->join('tbl_factory_production_setup_material_laravel as psml', function ($join) {
					$join->on('psml.batchNo', '=', 'psl.batchNo')
						->where('psml.material', '=', 1);
				})
				->join('tbl_factory_production_setup_material_laravel as psml2', function ($join) {
					$join->on('psml2.batchNo', '=', 'psl.batchNo')
						->where('psml2.material', '=', 2);
				})
				->join('tbl_factory_stringer_op_laravel as sol', 'psl.batchNo', '=', 'sol.batchNo')
				->orderBy('psl.created_at', 'DESC')
				->where('sol.id', $id)
				->first();
            $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.StringerOP.stringer-op-form-update', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}

	public function updateStringerOP(Request $request, $id)
	{
		$stage = ApprovalStage_Model::where('stage_module', '1753348533')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->first();

		$data = [
			'date'       => $request->date,
			'shift'      => $request->shift ?? '',
			'plant'     => $request->plant_no ?? '',
			'strNo'      => $request->strNo ?? null,
			'operator'   => $request->operator ?? null,
			'checker'    => $request->checker ?? null,
			'created_by' => auth()->id(),
			'status'     => 0,
			'stage'      => $stage ? $stage->id : null,
			'appr_process' => 0,
			'updated_at' => now(),
		];

		if (Factory_Stringer_Op::where('id', $id)->update($data)) {
			if ($request->has('materials')) {
				$materialIds = [];
				foreach ($request->materials as $material) {
					if (isset($material['id']) && !empty($material['id'])) {
						Factory_Stringer_Op_Material::where('id', $material['id'])->update([
							'material'        => $material['name'],
							'stringerNo'    => $material['stringer_no'],
							'time'            => $material['time'],
							'productionQty'  => $material['production_qty'],
							'RejectQty'   => $material['rejection_qty'],
							'reason'           => $material['stage'],
							'defectCat' => $material['defect_category']
						]);
						$materialIds[] = $material['id'];
					} else {
						$newMaterial = Factory_Stringer_Op_Material::create([
							'batchNo'         => $request->batchNo,
							'stringerOpId'    => $id,
							'material'        => $material['name'],
							'stringerNo'     => $material['stringer_no'],
							'time'            => $material['time'],
							'productionQty'  => $material['production_qty'],
							'RejectQty'   => $material['rejection_qty'],
							'reason'           => $material['stage'],
							'defectCat' => $material['defect_category']
						]);
						$materialIds[] = $newMaterial->id;
					}
				}
				Factory_Stringer_Op_Material::where('stringerOpId', $id)
					->whereNotIn('id', $materialIds)
					->delete();
			}

			$logData = [
				'id'         => time(),
				'stropId' => $id,
				'remarks'    => 'Verified',
				'action'     => 'Verified',
				'actionBy'   => auth()->id(),
				'ip'         => $this->getUserIP(),
				'created_at' => now(),
				'updated_at' => now(),
			];
			Factory_Stringer_Op_History::create($logData); // Assuming you have this model

			return redirect()->to(url('production-lineup/stringer-op'))->with('success', 'Stringer OP Request Updated Successfully');
		} else {
			return redirect()->to(url('production-lineup/stringer-op'))->with('failed', 'Something went wrong, please contact the System Administrator');
		}
	}
}
