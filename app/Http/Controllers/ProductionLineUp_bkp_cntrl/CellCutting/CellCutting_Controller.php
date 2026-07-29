<?php

namespace App\Http\Controllers\ProductionLineUp\CellCutting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\Factory_Cell_Cutting;
use App\Models\ProductionLineUp\Factory_Cell_Cutting_Material;
use App\Models\ProductionLineUp\Factory_Cell_Cutting_History;
use App\Models\Admin;
use App\Models\ApprovalMatrix\ApprovalStage_Model;
use Illuminate\Support\Facades\Auth;
use Exception;

class CellCutting_Controller extends Controller
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

	public function getCellCuttingMaterial(Request $request)
	{
		$batchNo = $request->query('q');

		$batchData = DB::table('tbl_factory_production_setup_laravel as psl')
			->select(
				'psl.*',
				'psml.size AS efficiency',
				'psml.brand',
				'asl.stage_title',
				'phl.actionBy',
				'b.fullname AS actionByName',
				'mml.title',
				'prj_material.material_name as matname'
			)
			->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
			->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
			->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($batchNo) {
				$join->on('psml.batchNo', '=', 'psl.batchNo')
					->where('psml.material', '=', 1);
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
			->join('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
			->orderBy('psl.created_at', 'DESC')
			->where('psl.batchNo', $batchNo)
			->get();
		foreach ($batchData as $batch)


			$materials[] = [
				'id' => 1 ?? 'N/A',
				'name' => $batch->title ?? 'N/A',
				'size' => $batch->efficiency ?? 'N/A',
				'brand' => $batch->brand ?? 'N/A'
			];

		return response()->json([
			'wattage' => $batch->wattage,
			'efficiency' => $batch->efficiency,
			'cell_company' => $batch->brand,
			'finishGood' => $batch->matname ?? '',
			'plantNo' => $batch->plantNo ?? '',
			"materials"   => $materials
		]);
	}

	public function index(Request $request)
	{
		$data['menu'] = 'cell-cutting';
		$data['empId'] = request()->session()->get('empId');
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		$Cond = [];
		$Condition = 'psml.material = 1';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "ccl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "ccl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "ccl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "ccl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "ccl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "ccl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}


		$sql = "
    		SELECT 
            ccl.*,
            psl.wattage,
            psml.size AS cellSize,
            psml.brand,
            sh.shift AS shiftdtl,
            SUM(ccml.productionQty) AS totalProductionQty,
            SUM(ccml.rejectQty) AS totalRejectQty,
            asl.stage_title,
            asl.stage_position,
            phl.actionBy,
            a.fullname AS addByName,
            b.fullname AS actionByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName
            FROM tbl_factory_cell_cutting_laravel AS ccl
            INNER JOIN tbl_factory_cell_cutting_material_laravel AS ccml 
                ON ccml.cellCuttingId = ccl.id
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON ccl.stage = asl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = ccl.shift
            LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = ccl.batchNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = ccl.batchNo
            LEFT JOIN (
                SELECT phl1.*
                FROM tbl_factory_cellcutting_hist_laravel AS phl1
                WHERE phl1.id = (
                    SELECT MAX(phl2.id)
                    FROM tbl_factory_cellcutting_hist_laravel AS phl2
                    WHERE phl2.cellId = phl1.cellId
                )
            ) AS phl 
                ON phl.cellId = ccl.id
            LEFT JOIN mstr_emp AS a 
                ON ccl.created_by = a.id
            LEFT JOIN mstr_emp AS b 
                ON phl.actionBy = b.id
            LEFT JOIN mstr_emp AS c 
                ON ccl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON ccl.checker = d.id
            WHERE $Condition
            GROUP BY ccml.cellCuttingId
            ORDER BY ccl.created_at DESC;
        ";
		$data['AllLists'] = DB::select($sql);

		/*$data['AllLists'] = DB::table('tbl_factory_cell_cutting_laravel as ccl')
			->select(
				'ccl.*',
				'psl.wattage',
				'psml.size as cellSize',
				'psml.brand',
				'sh.shift as shiftdtl',
				DB::raw('SUM(ccml.productionQty) as totalProductionQty'),
				DB::raw('SUM(ccml.rejectQty) as totalRejectQty'),
				'asl.stage_title',
				'asl.stage_position',
				'phl.actionBy',
				'a.fullname AS addByName',
				'b.fullname AS actionByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName'
			)
			->join('tbl_factory_cell_cutting_material_laravel as ccml', 'ccml.cellCuttingId', '=', 'ccl.id')
			->join('tbl_factory_appr_stage_laravel as asl', 'ccl.stage', '=', 'asl.id')
			->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ccl.shift')
			->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ccl.batchNo')
			->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'ccl.batchNo')
			->leftJoinSub(
				DB::table('tbl_factory_cellcutting_hist_laravel as phl1')
					->select('phl1.*')
					->whereRaw('phl1.id = (
					SELECT MAX(phl2.id) 
					FROM tbl_factory_cellcutting_hist_laravel as phl2
					WHERE phl2.cellId = phl1.cellId
				)'),
				'phl',
				function ($join) {
					$join->on('phl.cellId', '=', 'ccl.id');
				}
			)
			->leftJoin('mstr_emp as a', 'ccl.created_by', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'phl.actionBy', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'ccl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'ccl.checker', '=', 'd.id')
			->where('psml.material', 1)
			->groupBy('ccml.cellCuttingId')
			->orderBy('ccl.created_at', 'DESC')
			->get();
			
		*/
		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753102545')
			->orderBy('asl.stage_position', 'ASC')
			->get();

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();
        
		return view('ProductionLineUp.CellCutting.cell-cutting-request', $data);
	}

	public function Approval_list(Request $request)
	{
		$data['menu'] = 'cell-cutting-approval-list';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'psml.material = 1';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "ccl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "ccl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "ccl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "ccl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "ccl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "ccl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}

		$sql = "
    		SELECT 
            ccl.*,
            psl.wattage,
            psml.size AS cellSize,
            psml.brand,
            sh.shift AS shiftdtl,
            SUM(ccml.productionQty) AS totalProductionQty,
            SUM(ccml.rejectQty) AS totalRejectQty,
            asl.stage_title,
            asl.stage_position,
            phl.actionBy,
            a.fullname AS addByName,
            b.fullname AS actionByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName
            FROM tbl_factory_cell_cutting_laravel AS ccl
            INNER JOIN tbl_factory_cell_cutting_material_laravel AS ccml 
                ON ccml.cellCuttingId = ccl.id
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON ccl.stage = asl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = ccl.shift
            LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = ccl.batchNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = ccl.batchNo
            LEFT JOIN (
                SELECT phl1.*
                FROM tbl_factory_cellcutting_hist_laravel AS phl1
                WHERE phl1.id = (
                    SELECT MAX(phl2.id)
                    FROM tbl_factory_cellcutting_hist_laravel AS phl2
                    WHERE phl2.cellId = phl1.cellId
                )
            ) AS phl 
                ON phl.cellId = ccl.id
            LEFT JOIN mstr_emp AS a 
                ON ccl.created_by = a.id
            LEFT JOIN mstr_emp AS b 
                ON phl.actionBy = b.id
            LEFT JOIN mstr_emp AS c 
                ON ccl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON ccl.checker = d.id
            WHERE $Condition
            GROUP BY ccml.cellCuttingId
            ORDER BY ccl.created_at DESC;
        ";
		$data['AllLists'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753102545')
			->orderBy('asl.stage_position', 'ASC')
			->get();

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();
			$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.CellCutting.cell-cutting-approval-list', $data);
	}

	public function Detailed_list(Request $request)
	{
		$data['menu'] = 'cell-cutting-detailed';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'ccl.status IN (1)
    AND psml.material = 1';

		if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
			$Cond[] = "ccl.created_by = '" . $_GET['createdBy'] . "'";
		}
		if (isset($_GET['operator']) && $_GET['operator'] != '') {
			$Cond[] = "ccl.operator = '" . $_GET['operator'] . "'";
		}
		if (isset($_GET['checker']) && $_GET['checker'] != '') {
			$Cond[] = "ccl.checker = '" . $_GET['checker'] . "'";
		}
		if (isset($_GET['shift']) && $_GET['shift'] != '') {
			$Cond[] = "ccl.shift = '" . $_GET['shift'] . "'";
		}
		if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
			$Cond[] = "ccl.date >= '" . $_GET['fromDate'] . "'";
		}
		if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
			$Cond[] = "ccl.date <= '" . $_GET['toDate'] . "'";
		}
		if (count($Cond) > 0) {
			$Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
		}
		$sql = "
		SELECT 
    ccl.*,
    ccml.*,
    psl.wattage,
    psl.plantNo,
    psml.size as cellSize,
    psml.brand,
    sh.shift as shiftdtl,
    ccml.productionQty as totalProductionQty,
    ccml.rejectQty as totalRejectQty,
    asl.stage_title,
    phl.actionBy,
    a.fullname AS addByName,
    b.fullname AS actionByName,
    c.fullname AS operatorName,
    d.fullname AS checkerName,
    prj_material.material_name as matname
FROM tbl_factory_cell_cutting_laravel as ccl
INNER JOIN tbl_factory_cell_cutting_material_laravel as ccml 
    ON ccml.cellCuttingId = ccl.id
INNER JOIN tbl_factory_appr_stage_laravel as asl 
    ON ccl.stage = asl.id
LEFT JOIN hr_mstr_shift as sh 
    ON sh.id = ccl.shift
LEFT JOIN tbl_factory_production_setup_laravel as psl 
    ON psl.batchNo = ccl.batchNo
LEFT JOIN tbl_factory_production_setup_material_laravel as psml 
    ON psml.batchNo = ccl.batchNo
LEFT JOIN mstr_emp as a 
    ON ccl.created_by = a.id
LEFT JOIN mstr_emp as c 
    ON ccl.operator = c.id
LEFT JOIN mstr_emp as d 
    ON ccl.checker = d.id
LEFT JOIN materialmanagement_add_material 
    ON materialmanagement_add_material.id = psl.finishGood
LEFT JOIN prj_material 
    ON materialmanagement_add_material.Material_Name = prj_material.id
LEFT JOIN (
    SELECT phl1.*
    FROM tbl_factory_cellcutting_hist_laravel as phl1
    WHERE phl1.id = (
        SELECT MAX(phl2.id) 
        FROM tbl_factory_cellcutting_hist_laravel as phl2
        WHERE phl2.cellId = phl1.cellId
    )
) as phl 
    ON phl.cellId = ccl.id
LEFT JOIN mstr_emp as b 
    ON phl.actionBy = b.id
WHERE $Condition
ORDER BY ccl.created_at DESC;
		";
		$data['AllLists'] = DB::select($sql);

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->get();
$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.CellCutting.cell-cutting-detailed', $data);
	}

	public function add_cell_cutting(Request $request)
	{
		$data['menu'] = 'cell-cutting';
		$data['employees'] = Admin::get();
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
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
			->where('psml.material', 1)
			->where('psl.status', 1)
			->get();
$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.CellCutting.add-cell-cutting', $data);
	}

	public function view_cell_cutting(Request $request, $id)
	{

		$data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'cell-cutting';
		$data['id'] = $id;
		if ($id) {
			$data['cellCuttingDetails'] = DB::table('tbl_factory_cell_cutting_laravel as psl')
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
			$batchNo = $data['cellCuttingDetails']->batchNo;
			$data['cellCuttingMaterials'] = Factory_Cell_Cutting_Material::where('cellCuttingId', $id)->get();
			$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.plantNo',
					'psl.wattage',
					'psml.size AS efficiency',
					'psml.brand',
					'prj_material.material_name as matname'
				)
				->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
				->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
				->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($batchNo) {
					$join->on('psml.batchNo', '=', 'psl.batchNo')
						->where('psml.material', '=', 1);
				})->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
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
				->where('psml.material', 1)
				->where('psl.batchNo', $batchNo)
				->get();
			$data['cellCuttingHistory'] = DB::table('tbl_factory_cellcutting_hist_laravel AS c')
				->leftJoin('mstr_emp AS a', 'c.actionBy', '=', 'a.id')
				->select('c.*', 'a.fullname AS actionBy')
				->where('c.cellId', $id)
				->get();
		} else {
			$data['cellCuttingDetails'] = null;
			$data['cellCuttingMaterials'] = [];
		}
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.CellCutting.cell-cutting-view-request', $data);
	}

	public function approveDtls(Request $request)
	{
		if (request()->session()->has('empId')) {
			$batchNo = request()->value;
			if ($batchNo) {
				$data['cellCuttingDetails'] = DB::table('tbl_factory_cell_cutting_laravel as psl')
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
				$data['cellCuttingMaterials'] = Factory_Cell_Cutting_Material::where('cellCuttingId', $batchNo)->get();
				$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
					->select(
						'psl.plantNo',
						'psl.wattage',
						'psml.size AS efficiency',
						'psml.brand',
						'prj_material.material_name as matname'
					)
					->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
					->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
					->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($batchNo) {
						$join->on('psml.batchNo', '=', 'psl.batchNo')
							->where('psml.material', '=', 1);
					})->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
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
					->where('psml.material', 1)
					->where('psl.batchNo', $data['cellCuttingDetails']->batchNo)
					->get();
				$data['cellCuttingHistory'] = DB::table('tbl_factory_cellcutting_hist_laravel AS c')
					->leftJoin('mstr_emp AS a', 'c.actionBy', '=', 'a.id')
					->select('c.*', 'a.fullname AS actionBy')
					->where('c.cellId', $batchNo)
					->get();
			} else {
				$data['cellCuttingDetails'] = null;
				$data['cellCuttingMaterials'] = [];
			}

			$data['productSetDtls'] = DB::table('tbl_factory_cell_cutting_laravel as psl')
				->select(
					'psl.*',
					'asl.stage_title',
					'b.fullname AS actionByName'
				)
				->join('tbl_factory_cell_cutting_material_laravel as psml', 'psml.cellCuttingId', '=', 'psl.id')
				->join('tbl_factory_appr_stage_laravel as asl', 'psl.stage', '=', 'asl.id')
				->leftJoinSub(
					DB::table('tbl_factory_cellcutting_hist_laravel as phl1')
						->select('phl1.*')
						->whereRaw('phl1.id = (
            SELECT MAX(phl2.id)
            FROM tbl_factory_cellcutting_hist_laravel as phl2
            WHERE phl2.cellId = phl1.cellId
          )'),
					'phl',
					'phl.cellId',
					'=',
					'psl.id'
				)
				->leftJoin('mstr_emp as b', 'phl.actionBy', '=', 'b.id')
				->orderBy('psl.created_at', 'DESC')
				->where('psl.id', $batchNo)
				->get();
			$data['productSetTrail'] = DB::table('tbl_factory_cellcutting_hist_laravel')
				->select('tbl_factory_cellcutting_hist_laravel.*', 'b.fullname')
				->join('mstr_emp AS b', 'tbl_factory_cellcutting_hist_laravel.actionBy', '=', 'b.id')
				->where('tbl_factory_cellcutting_hist_laravel.cellId', $batchNo)
				->get();
			
		    $data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'cell-cutting';
		    $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.CellCutting.approve-details', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
		}
	}

	public function store_cell_cutting(Request $request)
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

		$stages = ApprovalStage_Model::where('stage_module', '1753102545')
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
			'operator' => $request->operator ?? null,
			'checker' => $request->checker ?? null,
			'created_by' => auth()->id(),
			'status' => 0,
			'stage' => $stage->id ?? null,
			'created_at' => now(),
			'updated_at' => now(),
		];
		$res = Factory_Cell_Cutting::create($data);

		if ($res->exists) {
			$materials  = request()->input('materials');
			foreach ($materials as $key => $value) {
				// echo $request->material[$key];exit;
				$materialData = [
					'batchNo' => $request->batch_no,
					'cellCuttingId' => $id,
					'material' => $materials[$key]['name'],
					'time' => $materials[$key]['time'],
					'productionQty' => $materials[$key]['production_qty'],
					'RejectQty' => $materials[$key]['rejection_qty'],
					'reason' => $materials[$key]['stage'],
					'defectCat' => $materials[$key]['defect_category']
				];
				Factory_Cell_Cutting_Material::create($materialData);
			}

			$logData = [
				'id' => time(),
				'cellId' => $id,
				'remarks' => 'Request Raised',
				'action' => 'Raised',
				'actionBy' => auth()->id(),
				'ip' => $this->getUserIP(),
			];

			Factory_Cell_Cutting_History::create($logData);

			return redirect('production-lineup/cell-cutting')->with('success', 'Cell Cutting Request Created Successfully');
		} else {
			return redirect('production-lineup/cell-cutting')->with('failed', 'Something went wrong, please contact the System Administrator');
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
					->where('stage_module', '1753102545')
					->where('stage_stat', '1')
					->orderBy('id', 'asc')
					->limit(1)
					->get();

				foreach ($nextPositionIdObj as $nextPosition);

				if (count($nextPositionIdObj) > 0) {
					$nextStage = $nextPositionIdObj[0]['id'];

					$result = Factory_Cell_Cutting::where('id', $batchNo);
					$input['stage'] = $nextStage;
					$input['status'] = 0;
					$input['appr_process'] = 1;
					$res = $result->update($input);
				} else {
					$result = Factory_Cell_Cutting::where('id', $batchNo);
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

				$result = Factory_Cell_Cutting::where('id', $batchNo);
				$input['status'] = request()->input('ApprStat');
				$input['appr_process'] = 1;
				$res = $result->update($input);
			}

			if ($res == 1) {
				$data = array(
					'id'              => time(),
					'cellId'         => $batchNo,
					'remarks'         => $remarks,
					'action'          => $ActionString,
					'actionBy'        => request()->session()->get('empId'),
					'ip'              => $this->getUserIP(),
				);

				$res = Factory_Cell_Cutting_History::create($data);

				return redirect()->to(url('production-lineup/cell-cutting-approval-list'))->with('success', 'Approval Action done Successfully');
			} else {
				return redirect()->to(url('production-lineup/cell-cutting-approval-list'))->with('failed', 'Something went wrong contact with System Aministrator');
			}
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}

	public function formUpdateView($id)
	{
		if (request()->session()->has('empId')) {
			$data['cellCutting'] = Factory_Cell_Cutting::find($id);
			$data['menu'] = 'cell-cutting';
			$data['employees'] = Admin::get();
			$data['ShiftMaster'] = DB::table('hr_mstr_shift')
				->select('hr_mstr_shift.*')
				->get();

			// Only single material: Cell (id=1)
			$data['materialMaster'] = DB::table('tbl_factory_material_master_laravel')
				->select('id', 'title')
				->where('id', 1)
				->get();

			// Modified query to get materials correctly
			$data['cellCuttingMaterials'] = DB::table('tbl_factory_cell_cutting_material_laravel as psml')
				->select(
					'psml.id',
					'psml.material as mat_id',
					'psml.time',
					'psml.productionQty',
					'psml.RejectQty',
					'psml.reason',
					'psml.defectCat',
					'mml.title as material_name'
				)
				->leftJoin('tbl_factory_material_master_laravel as mml', 'psml.material', '=', 'mml.id')
				->where('psml.cellCuttingId', $id)
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
				->where('psml.material', 1)
				->get();
			$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.plantNo',
					'psl.wattage',
					'psml.size AS efficiency',
					'psml.brand',
					'prj_material.material_name as matname'
				)
				->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
				->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
				->join('tbl_factory_production_setup_material_laravel as psml', function ($join) use ($id) {
					$join->on('psml.batchNo', '=', 'psl.batchNo')
						->where('psml.material', '=', 1);
				})
				->join('tbl_factory_cell_cutting_laravel as ccl', 'psl.batchNo', '=', 'ccl.batchNo')
				->orderBy('psl.created_at', 'DESC')
				->where('ccl.id', $id)
				->where('psl.status', 1)
				->first();
				$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.CellCutting.cell-cutting-update', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}

	public function updateCellCutting(Request $request, $id)
	{
		$stage = ApprovalStage_Model::where('stage_module', '1753102545')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->first();

		$data = [
			'date'       => $request->date,
			'shift'      => $request->shift ?? '',
			'operator'   => $request->operator ?? null,
			'checker'    => $request->checker ?? null,
			'created_by' => auth()->id(),
			'status'     => 0,
			'stage'      => $stage ? $stage->id : null,
			'appr_process' => 0,
			'updated_at' => now(),
		];

		if (Factory_Cell_Cutting::where('id', $id)->update($data)) {
			if ($request->has('materials')) {
				$materialIds = [];
				foreach ($request->materials as $material) {
					if (isset($material['id']) && !empty($material['id'])) {
						Factory_Cell_Cutting_Material::where('id', $material['id'])->update([
							'material'      => $material['name'],
							'time'          => $material['time'],
							'productionQty' => $material['production_qty'],
							'RejectQty'     => $material['rejection_qty'],
							'reason'        => $material['stage'],
							'defectCat'     => $material['defect_category']
						]);
						$materialIds[] = $material['id'];
					} else {
						$newMaterial = Factory_Cell_Cutting_Material::create([
							'batchNo'       => $request->batchNo,
							'cellCuttingId' => $id,
							'material'      => $material['name'],
							'time'          => $material['time'],
							'productionQty' => $material['production_qty'],
							'RejectQty'     => $material['rejection_qty'],
							'reason'        => $material['stage'],
							'defectCat'     => $material['defect_category']
						]);
						$materialIds[] = $newMaterial->id;
					}
				}
				Factory_Cell_Cutting_Material::where('cellCuttingId', $id)
					->whereNotIn('id', $materialIds)
					->delete();
			}

			$logData = [
				'id'        => time(),
				'cellId'    => $id,
				'remarks'   => 'Verified',
				'action'    => 'Verified',
				'actionBy'  => auth()->id(),
				'ip'        => $this->getUserIP(),
				'created_at' => now(),
				'updated_at' => now(),
			];
			Factory_Cell_Cutting_History::create($logData);

			return redirect()->to(url('production-lineup/cell-cutting'))->with('success', 'Cell Cutting Request Updated Successfully');
		} else {
			return redirect()->to(url('production-lineup/cell-cutting'))->with('failed', 'Something went wrong, please contact the System Administrator');
		}
	}
}
