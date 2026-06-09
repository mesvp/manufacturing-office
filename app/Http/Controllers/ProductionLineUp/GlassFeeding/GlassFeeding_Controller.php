<?php

namespace App\Http\Controllers\ProductionLineUp\GlassFeeding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\ProductionLineUp\{materialMaster_Model, ProductSetUp_Model, ProductSetUpMaterial_Model};
use App\Models\ProductionLineUp\{GlassFeedingHist_Model, GlassFeeding_Model, GlassFeedingMaterial_Model};
use App\Models\ApprovalMatrix\ApprovalStage_Model;
use App\Models\ApprovalMatrix\Approver_Model;

class GlassFeeding_Controller extends Controller
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
  
	public function index(Request $request)
	{
		$data['menu'] = 'glass-feeding';
		$data['empId'] = request()->session()->get('empId');
		$Cond = [];
		$Condition = 'psml.material = 3';
		
		if(isset($_GET['createdBy']) && $_GET['createdBy'] != ''){
		    $Cond[] = "gfl.created_by = '".$_GET['createdBy']."'";
		}
		if(isset($_GET['operator']) && $_GET['operator'] != ''){
		    $Cond[] = "gfl.operator = '".$_GET['operator']."'";
		}
		if(isset($_GET['checker']) && $_GET['checker'] != ''){
		    $Cond[] = "gfl.checker = '".$_GET['checker']."'";
		}
		if(isset($_GET['shift']) && $_GET['shift'] != ''){
		    $Cond[] = "gfl.shift = '".$_GET['shift']."'";
		}
		if(isset($_GET['fromDate']) && $_GET['fromDate'] != ''){
		    $Cond[] = "gfl.date >= '".$_GET['fromDate']."'";
		}
		if(isset($_GET['toDate']) && $_GET['toDate'] != ''){
		    $Cond[] = "gfl.date <= '".$_GET['toDate']."'";
		}
		if(count($Cond) > 0){
		  $Condition = $Condition.' AND '.implode(' AND ', $Cond);
		}
		
		//echo $Condition;exit;
				$sql = "SELECT 
            gfl.*,
            gfhl.actionBy,
            a.fullname AS actionByName,
            psl.wattage,
            psml.size AS glassSize,
            SUM(gfml.productionQty) AS totalProductionQty,
            SUM(gfml.rejectQty) AS totalRejectQty,
            b.fullname AS addByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName,
            asl.stage_title,
            asl.stage_position,
            sh.shift AS shiftdtl
            FROM tbl_factory_glass_feed_laravel AS gfl
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON gfl.stage = asl.id
            INNER JOIN tbl_factory_production_setup_laravel AS psl 
                ON gfl.batchNo = psl.batchNo
            INNER JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = psl.batchNo
            INNER JOIN tbl_factory_glass_feed_material_laravel AS gfml 
                ON gfml.glassFeedId = gfl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = gfl.shift
            LEFT JOIN (
                SELECT gfhl1.*
                FROM tbl_factory_glass_feed_hist_laravel AS gfhl1
                WHERE gfhl1.id = (
                    SELECT MAX(gfhl2.id)
                    FROM tbl_factory_glass_feed_hist_laravel AS gfhl2
                    WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
                )
            ) AS gfhl 
                ON gfhl.glassFeedId = gfl.id
            LEFT JOIN mstr_emp AS a 
                ON gfhl.actionBy = a.id
            LEFT JOIN mstr_emp AS b 
                ON gfl.created_by = b.id
            LEFT JOIN mstr_emp AS c 
                ON gfl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON gfl.checker = d.id
            WHERE $Condition
            GROUP BY gfml.glassFeedId
            ORDER BY gfl.created_at DESC;
        ";

        $data['allList'] = DB::select($sql);
        
		/*$data['allList'] = DB::table('tbl_factory_glass_feed_laravel as gfl')
			->select(
				'gfl.*',
				'gfhl.actionBy',
				'a.fullname AS actionByName',
				'psl.wattage',
				'psml.size AS glassSize',
				DB::raw('SUM(gfml.productionQty) as totalProductionQty'),
				DB::raw('SUM(gfml.rejectQty) as totalRejectQty'),
				'b.fullname AS addByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName',
				'asl.stage_title',
				'asl.stage_position',
				'sh.shift as shiftdtl'
			)
			->join('tbl_factory_appr_stage_laravel as asl', 'gfl.stage', '=', 'asl.id')
			->join('tbl_factory_production_setup_laravel as psl', 'gfl.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_glass_feed_material_laravel as gfml', 'gfml.glassFeedId', '=', 'gfl.id')
			->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'gfl.shift')
			->leftJoinSub(
				DB::table('tbl_factory_glass_feed_hist_laravel as gfhl1')
					->select('gfhl1.*')
					->whereRaw('gfhl1.id = (
          SELECT MAX(gfhl2.id)
          FROM tbl_factory_glass_feed_hist_laravel as gfhl2
          WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
        )'),
				'gfhl',
				'gfhl.glassFeedId',
				'=',
				'gfl.id'
			)
			->leftJoin('mstr_emp as a', 'gfhl.actionBy', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'gfl.created_by', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'gfl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'gfl.checker', '=', 'd.id')
			->where('psml.material', '3')
			->orderBy('gfl.created_at', 'DESC')
			->groupBy('gfml.glassFeedId')
			->get();
			*/
		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753341338')
			->orderBy('asl.stage_position', 'ASC')
			->get();
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
		
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding', $data);
	}
	
	
		public function GlassFeedingAll(Request $request)
	{
		$data['menu'] = 'glass-feeding-all';
		$data['empId'] = request()->session()->get('empId');
		$Cond = [];
		$Condition = 'psml.material = 3';
		
		if(isset($_GET['createdBy']) && $_GET['createdBy'] != ''){
		    $Cond[] = "gfl.created_by = '".$_GET['createdBy']."'";
		}
		if(isset($_GET['operator']) && $_GET['operator'] != ''){
		    $Cond[] = "gfl.operator = '".$_GET['operator']."'";
		}
		if(isset($_GET['checker']) && $_GET['checker'] != ''){
		    $Cond[] = "gfl.checker = '".$_GET['checker']."'";
		}
		if(isset($_GET['shift']) && $_GET['shift'] != ''){
		    $Cond[] = "gfl.shift = '".$_GET['shift']."'";
		}
		if(isset($_GET['fromDate']) && $_GET['fromDate'] != ''){
		    $Cond[] = "gfl.date >= '".$_GET['fromDate']."'";
		}
		if(isset($_GET['toDate']) && $_GET['toDate'] != ''){
		    $Cond[] = "gfl.date <= '".$_GET['toDate']."'";
		}
		if(count($Cond) > 0){
		  $Condition = $Condition.' AND '.implode(' AND ', $Cond);
		}
		
		//echo $Condition;exit;
				$sql = "SELECT 
            gfl.*,
            gfhl.actionBy,
            a.fullname AS actionByName,
            psl.wattage,
            psml.size AS glassSize,
            SUM(gfml.productionQty) AS totalProductionQty,
            SUM(gfml.rejectQty) AS totalRejectQty,
            b.fullname AS addByName,
            c.fullname AS operatorName,
            d.fullname AS checkerName,
            asl.stage_title,
            asl.stage_position,
            sh.shift AS shiftdtl
            FROM tbl_factory_glass_feed_laravel AS gfl
            INNER JOIN tbl_factory_appr_stage_laravel AS asl 
                ON gfl.stage = asl.id
            INNER JOIN tbl_factory_production_setup_laravel AS psl 
                ON gfl.batchNo = psl.batchNo
            INNER JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = psl.batchNo
            INNER JOIN tbl_factory_glass_feed_material_laravel AS gfml 
                ON gfml.glassFeedId = gfl.id
            LEFT JOIN hr_mstr_shift AS sh 
                ON sh.id = gfl.shift
            LEFT JOIN (
                SELECT gfhl1.*
                FROM tbl_factory_glass_feed_hist_laravel AS gfhl1
                WHERE gfhl1.id = (
                    SELECT MAX(gfhl2.id)
                    FROM tbl_factory_glass_feed_hist_laravel AS gfhl2
                    WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
                )
            ) AS gfhl 
                ON gfhl.glassFeedId = gfl.id
            LEFT JOIN mstr_emp AS a 
                ON gfhl.actionBy = a.id
            LEFT JOIN mstr_emp AS b 
                ON gfl.created_by = b.id
            LEFT JOIN mstr_emp AS c 
                ON gfl.operator = c.id
            LEFT JOIN mstr_emp AS d 
                ON gfl.checker = d.id
            WHERE $Condition
            GROUP BY gfml.glassFeedId
            ORDER BY gfl.created_at DESC;
        ";

        $data['allList'] = DB::select($sql);
        
		/*$data['allList'] = DB::table('tbl_factory_glass_feed_laravel as gfl')
			->select(
				'gfl.*',
				'gfhl.actionBy',
				'a.fullname AS actionByName',
				'psl.wattage',
				'psml.size AS glassSize',
				DB::raw('SUM(gfml.productionQty) as totalProductionQty'),
				DB::raw('SUM(gfml.rejectQty) as totalRejectQty'),
				'b.fullname AS addByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName',
				'asl.stage_title',
				'asl.stage_position',
				'sh.shift as shiftdtl'
			)
			->join('tbl_factory_appr_stage_laravel as asl', 'gfl.stage', '=', 'asl.id')
			->join('tbl_factory_production_setup_laravel as psl', 'gfl.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_glass_feed_material_laravel as gfml', 'gfml.glassFeedId', '=', 'gfl.id')
			->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'gfl.shift')
			->leftJoinSub(
				DB::table('tbl_factory_glass_feed_hist_laravel as gfhl1')
					->select('gfhl1.*')
					->whereRaw('gfhl1.id = (
          SELECT MAX(gfhl2.id)
          FROM tbl_factory_glass_feed_hist_laravel as gfhl2
          WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
        )'),
				'gfhl',
				'gfhl.glassFeedId',
				'=',
				'gfl.id'
			)
			->leftJoin('mstr_emp as a', 'gfhl.actionBy', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'gfl.created_by', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'gfl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'gfl.checker', '=', 'd.id')
			->where('psml.material', '3')
			->orderBy('gfl.created_at', 'DESC')
			->groupBy('gfml.glassFeedId')
			->get();
			*/
		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753341338')
			->orderBy('asl.stage_position', 'ASC')
			->get();
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
		
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding-all', $data);
	}


	public function Approval_list(Request $request)
	{
		$data['menu'] = 'glass-feeding-approval-list';
		$data['empId'] = request()->session()->get('empId');

		$Cond = [];
		$Condition = 'psml.material = 3 AND gfl.status <> 1 AND gfl.status <> 4';

		if(isset($_GET['createdBy']) && $_GET['createdBy'] != ''){
		    $Cond[] = "gfl.created_by = '".$_GET['createdBy']."'";
		}
		if(isset($_GET['operator']) && $_GET['operator'] != ''){
		    $Cond[] = "gfl.operator = '".$_GET['operator']."'";
		}
		if(isset($_GET['checker']) && $_GET['checker'] != ''){
		    $Cond[] = "gfl.checker = '".$_GET['checker']."'";
		}
		if(isset($_GET['shift']) && $_GET['shift'] != ''){
		    $Cond[] = "gfl.shift = '".$_GET['shift']."'";
		}
		if(isset($_GET['fromDate']) && $_GET['fromDate'] != ''){
		    $Cond[] = "gfl.date >= '".$_GET['fromDate']."'";
		}
		if(isset($_GET['toDate']) && $_GET['toDate'] != ''){
		    $Cond[] = "gfl.date <= '".$_GET['toDate']."'";
		}
		if(count($Cond) > 0){
		  $Condition = $Condition.' AND '.implode(' AND ', $Cond);
		}

		$sql = "SELECT 
    gfl.*,
    gfhl.actionBy,
    a.fullname AS actionByName,
    psl.wattage,
    psml.size AS glassSize,
    SUM(gfml.productionQty) as totalProductionQty,
    SUM(gfml.rejectQty) as totalRejectQty,
    b.fullname AS addByName,
    c.fullname AS operatorName,
    d.fullname AS checkerName,
    asl.stage_title,
    psml.uom,
    sh.shift as shiftdtl
FROM tbl_factory_glass_feed_laravel as gfl
INNER JOIN tbl_factory_appr_stage_laravel as asl 
    ON gfl.stage = asl.id
INNER JOIN tbl_factory_production_setup_laravel as psl 
    ON gfl.batchNo = psl.batchNo
INNER JOIN tbl_factory_production_setup_material_laravel as psml 
    ON psml.batchNo = psl.batchNo
INNER JOIN tbl_factory_glass_feed_material_laravel as gfml 
    ON gfml.glassFeedId = gfl.id
LEFT JOIN hr_mstr_shift as sh 
    ON sh.id = gfl.shift
LEFT JOIN (
    SELECT gfhl1.*
    FROM tbl_factory_glass_feed_hist_laravel as gfhl1
    WHERE gfhl1.id = (
        SELECT MAX(gfhl2.id)
        FROM tbl_factory_glass_feed_hist_laravel as gfhl2
        WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
    )
) as gfhl 
    ON gfhl.glassFeedId = gfl.id
LEFT JOIN mstr_emp as a 
    ON gfhl.actionBy = a.id
LEFT JOIN mstr_emp as b 
    ON gfl.created_by = b.id
LEFT JOIN mstr_emp as c 
    ON gfl.operator = c.id
LEFT JOIN mstr_emp as d 
    ON gfl.checker = d.id
WHERE $Condition
GROUP BY gfml.glassFeedId
ORDER BY gfl.created_at DESC";

		$data['allList'] = DB::select($sql);

		$data['approverDetails'] = DB::table('tbl_factory_appr_laravel AS al')
			->select('al.*', 'a.fullname as Approver')
			->join('tbl_factory_appr_stage_laravel AS asl', 'al.stage_id', '=', 'asl.id')
			->join('mstr_emp AS a', 'al.person_id', '=', 'a.id')
			->where('asl.stage_module', '1753341338')
			->orderBy('asl.stage_position', 'ASC')
			->get();

		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();

		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding-approval-list', $data);
	}

	public function Detailed_list(Request $request)
	{
		$data['menu'] = 'glass-feeding-detailed';

		$Cond = [];
		$Condition = 'gfl.status = 1 AND psml.material = 3';

		if(isset($_GET['createdBy']) && $_GET['createdBy'] != ''){
		    $Cond[] = "gfl.created_by = '".$_GET['createdBy']."'";
		}
		if(isset($_GET['operator']) && $_GET['operator'] != ''){
		    $Cond[] = "gfl.operator = '".$_GET['operator']."'";
		}
		if(isset($_GET['checker']) && $_GET['checker'] != ''){
		    $Cond[] = "gfl.checker = '".$_GET['checker']."'";
		}
		if(isset($_GET['shift']) && $_GET['shift'] != ''){
		    $Cond[] = "gfl.shift = '".$_GET['shift']."'";
		}
		if(isset($_GET['fromDate']) && $_GET['fromDate'] != ''){
		    $Cond[] = "gfl.date >= '".$_GET['fromDate']."'";
		}
		if(isset($_GET['toDate']) && $_GET['toDate'] != ''){
		    $Cond[] = "gfl.date <= '".$_GET['toDate']."'";
		}
		if(count($Cond) > 0){
		  $Condition = $Condition.' AND '.implode(' AND ', $Cond);
		}

		$sql = "SELECT 
    gfl.*,
    gfhl.actionBy,
    a.fullname AS actionByName,
    psl.wattage,
    gfml.size AS glassSize,
    b.fullname AS addByName,
    c.fullname AS operatorName,
    d.fullname AS checkerName,
    asl.stage_title,
    gfml.time,
    gfml.productionQty,
    gfml.RejectQty,
    gfml.reason,
    gfml.defectCat,
    mml.title,
    mml.uom AS matUOM,
    psml.uom,
    psml.brand,
    sh.shift as shiftdtl,
    prj_material.material_name as matname
FROM tbl_factory_glass_feed_laravel as gfl
INNER JOIN tbl_factory_appr_stage_laravel as asl 
    ON gfl.stage = asl.id
INNER JOIN tbl_factory_production_setup_laravel as psl 
    ON gfl.batchNo = psl.batchNo
INNER JOIN tbl_factory_production_setup_material_laravel as psml 
    ON psml.batchNo = psl.batchNo
LEFT JOIN hr_mstr_shift as sh 
    ON sh.id = gfl.shift
LEFT JOIN materialmanagement_add_material 
    ON materialmanagement_add_material.id = psl.finishGood
LEFT JOIN prj_material 
    ON materialmanagement_add_material.Material_Name = prj_material.id
LEFT JOIN (
    SELECT gfhl1.*
    FROM tbl_factory_glass_feed_hist_laravel as gfhl1
    WHERE gfhl1.id = (
        SELECT MAX(gfhl2.id)
        FROM tbl_factory_glass_feed_hist_laravel as gfhl2
        WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
    )
) as gfhl 
    ON gfhl.glassFeedId = gfl.id
LEFT JOIN mstr_emp as a 
    ON gfhl.actionBy = a.id
LEFT JOIN mstr_emp as b 
    ON gfl.created_by = b.id
LEFT JOIN mstr_emp as c 
    ON gfl.operator = c.id
LEFT JOIN mstr_emp as d 
    ON gfl.checker = d.id
INNER JOIN tbl_factory_glass_feed_material_laravel as gfml 
    ON gfml.glassFeedId = gfl.id
INNER JOIN tbl_factory_material_master_laravel as mml 
    ON gfml.material = mml.id
WHERE $Condition
ORDER BY gfl.created_at DESC";
		$data['allList'] = DB::select($sql);
		$data['ShiftMaster'] = DB::table('hr_mstr_shift')
			->select('hr_mstr_shift.*')
			->get();
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
			->get();
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding-detailed', $data);
	}

	public function add_glass_feeding(Request $request)
	{
		$data['menu'] = 'glass-feeding';
		$data['userList'] = DB::table('mstr_emp')
			->select('mstr_emp.*')
			->where('mstr_emp.status', '1')
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
			->where('psml.material', 3)
			->where('psl.status', 1)
			->get();
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
			
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.add-glass-feeding', $data);
	}

	public function view_glass_feeding(Request $request, $id = null)
	{
		$data['glassFeedDtls'] = DB::table('tbl_factory_glass_feed_laravel as gfl')
			->select(
				'gfl.*',
				'gfhl.actionBy',
				'a.fullname AS actionByName',
				'psl.wattage',
				'psml.size AS glassSize',
				'b.fullname AS addByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName',
				'asl.stage_title',
				'psml2.size AS efficiency',
				'psml2.brand',
				'sh.shift as shiftdtl',
				'prj_material.material_name as matname'
			)
			->join('tbl_factory_appr_stage_laravel as asl', 'gfl.stage', '=', 'asl.id')
			->join('tbl_factory_production_setup_laravel as psl', 'gfl.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml2', 'psml2.batchNo', '=', 'psl.batchNo')
			->leftjoin('hr_mstr_shift as sh', 'sh.id', '=', 'gfl.shift')
			->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
			->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
			->leftJoinSub(
				DB::table('tbl_factory_glass_feed_hist_laravel as gfhl1')
					->select('gfhl1.*')
					->whereRaw('gfhl1.id = (
          SELECT MAX(gfhl2.id)
          FROM tbl_factory_glass_feed_hist_laravel as gfhl2
          WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
        )'),
				'gfhl',
				'gfhl.glassFeedId',
				'=',
				'gfl.id'
			)
			->leftJoin('mstr_emp as a', 'gfhl.actionBy', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'gfl.created_by', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'gfl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'gfl.checker', '=', 'd.id')
			->orderBy('gfl.created_at', 'DESC')
			->where('psml.material', '3')
			->where('psml2.material', '1')
			->where('gfl.id', $id)
			->get();
		$data['glassFeedMtrl'] = DB::table('tbl_factory_glass_feed_material_laravel as gfml')
			->select('gfml.*', 'mml.title')
			->join('tbl_factory_material_master_laravel AS mml', 'mml.id', '=', 'gfml.material')
			->where('gfml.glassFeedId', $id)
			->get();
		$data['glassFeedTrail'] = DB::table('tbl_factory_glass_feed_hist_laravel AS gfhl')
			->select('gfhl.*', 'b.fullname')
			->join('mstr_emp AS b', 'gfhl.actionBy', '=', 'b.id')
			->where('gfhl.glassFeedId', $id)
			->get();

		
		$data['menu'] = (isset($_GET['menu']))?$_GET['menu']:'glass-feeding';
		$data['id'] = $id;

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding-view-request', $data);
	}

	public function getGlassMaterial(Request $request)
	{
		$batchNo = $request->query('q');

		$batchData =  DB::table('tbl_factory_production_setup_laravel as psl')
			->select(
				'psl.*',
				'psml.size AS efficiency',
				'psml.brand',
				'psml1.size AS glassSize',
				'asl.stage_title',
				'phl.actionBy',
				'b.fullname AS actionByName',
				'mml.title',
				'prj_material.material_name as matname'
			)
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml1', 'psml1.batchNo', '=', 'psl.batchNo')
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
			->join('tbl_factory_material_master_laravel as mml', 'psml1.material', '=', 'mml.id')
			->orderBy('psl.created_at', 'DESC')
			->where('psl.batchNo', $batchNo)
			->where('psml.material', 1)
			->where('psml1.material', 3)
			->get();

		foreach ($batchData as $batch)


			$materials[] = [
				'id' => 3 ?? 'N/A',
				'name' => $batch->title ?? 'N/A',
				'glassSize' => $batch->glassSize ?? 'N/A',
			];

		return response()->json([
			'wattage' => $batch->wattage,
			'efficiency' => $batch->efficiency,
			'finishGood' => $batch->matname ?? '',
			'cell_company' => $batch->brand,
			'glass_size' => $batch->glassSize,
			"materials"   => $materials
		]);
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

	public function insert(Request $request)
	{
		//print_r($_POST);
		//exit;


		$stages = ApprovalStage_Model::where('stage_module', '1753341338')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->limit(1)->get();
		foreach ($stages as $stage);
		$id = time();
		$data = array(
			'id'            => $id,
			'batchNo'       => request()->input('batch_no'),
			'date'       => request()->input('date'),
			'shift'     => request()->input('shift'),
			'plant'     => request()->input('plant_no'),
			'operator'       => request()->input('operator'),
			'checker'    => request()->input('checker'),
			'status'        => '0',
			'stage'         => $stage['id'],
			'created_by'    => request()->session()->get('empId')
		);

		$res = GlassFeeding_Model::create($data);
		if ($res->exists) {

			$materials  = request()->input('materials');

			foreach ($materials as $key => $value) {
				$data = array(
					'glassFeedId'     => $id,
					'material'        => $materials[$key]['name'],
					'size'            => $materials[$key]['size'],
					'time'            => $materials[$key]['time'],
					'productionQty'   => $materials[$key]['production_qty'],
					'RejectQty'       => $materials[$key]['rejection_qty'],
					'reason'          => $materials[$key]['stage'],
					'defectCat'       => $materials[$key]['defect_category']
				);

				GlassFeedingMaterial_Model::create($data);
			}


			$data = array(
				'id'           => time(),
				'glassFeedId'  => $id,
				'remarks'      => 'Raised',
				'action'       => 'Raised',
				'actionBy'     => request()->session()->get('empId'),
				'ip'           => $this->getUserIP(),
			);

			$res = GlassFeedingHist_Model::create($data);

			return redirect()->to(url('production-lineup/glass-feeding'))->with('success', 'Glass Feeding Request added Successfully');
		} else {
			return redirect()->to(url('production-lineup/glass-feeding'))->with('failed', 'Something went wrong contact with System Aministrator');
		}
	}



	public function approveDtls(Request $request)
	{
		$id = request()->value;

		$data['glassFeedDtls'] = DB::table('tbl_factory_glass_feed_laravel as gfl')
			->select(
				'gfl.*',
				'gfhl.actionBy',
				'a.fullname AS actionByName',
				'psl.wattage',
				'psml.size AS glassSize',
				'b.fullname AS addByName',
				'c.fullname AS operatorName',
				'd.fullname AS checkerName',
				'asl.stage_title',
				'psml2.size AS efficiency',
				'psml2.brand',
				'sh.shift as shiftdtl',
				'prj_material.material_name as matname'
)
			->join('tbl_factory_appr_stage_laravel as asl', 'gfl.stage', '=', 'asl.id')
			->join('tbl_factory_production_setup_laravel as psl', 'gfl.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
			->join('tbl_factory_production_setup_material_laravel as psml2', 'psml2.batchNo', '=', 'psl.batchNo')
			->leftjoin('hr_mstr_shift as sh', 'sh.id', '=', 'gfl.shift')
			->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
			->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
			->leftJoinSub(
				DB::table('tbl_factory_glass_feed_hist_laravel as gfhl1')
					->select('gfhl1.*')
					->whereRaw('gfhl1.id = (
          SELECT MAX(gfhl2.id)
          FROM tbl_factory_glass_feed_hist_laravel as gfhl2
          WHERE gfhl2.glassFeedId = gfhl1.glassFeedId
        )'),
				'gfhl',
				'gfhl.glassFeedId',
				'=',
				'gfl.id'
			)
			->leftJoin('mstr_emp as a', 'gfhl.actionBy', '=', 'a.id')
			->leftJoin('mstr_emp as b', 'gfl.created_by', '=', 'b.id')
			->leftJoin('mstr_emp as c', 'gfl.operator', '=', 'c.id')
			->leftJoin('mstr_emp as d', 'gfl.checker', '=', 'd.id')
			->orderBy('gfl.created_at', 'DESC')
			->where('psml.material', '3')
			->where('psml2.material', '1')
			->where('gfl.id', $id)
			->get();
		$data['glassFeedMtrl'] = DB::table('tbl_factory_glass_feed_material_laravel as gfml')
			->select('gfml.*', 'mml.title')
			->join('tbl_factory_material_master_laravel AS mml', 'mml.id', '=', 'gfml.material')
			->where('gfml.glassFeedId', $id)
			->get();
		$data['glassFeedTrail'] = DB::table('tbl_factory_glass_feed_hist_laravel AS gfhl')
			->select('gfhl.*', 'b.fullname')
			->join('mstr_emp AS b', 'gfhl.actionBy', '=', 'b.id')
			->where('gfhl.glassFeedId', $id)
			->get();

		$data['menu'] = 'glass-feeding-approval-list';
		
		$data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
		return view('ProductionLineUp.GlassFeeding.glass-feeding-appr-request', $data);
	}
	public function approvalAction(Request $request)
	{
		if (request()->session()->has('empId')) {
			// request()->value;
			$remarks = request()->input('remark');
			$submitData = request()->input('submitData');
			$submitData = explode('_', $submitData);
			$glassFeedId = $submitData[0];
			$current_Stage = $submitData[1];

			if (request()->input('ApprStat') == 1) {

				$ActionString = $submitData[2] . " Approved";

				//$nextPositionIdObj = [];
				$nextPositionIdObj = ApprovalStage_Model::where('id', '>', $current_Stage)
					->where('stage_module', '1753341338')
					->where('stage_stat', '1')
					->orderBy('id', 'asc')
					->limit(1)
					->get();

				foreach ($nextPositionIdObj as $nextPosition);

				if (count($nextPositionIdObj) > 0) {
					$nextStage = $nextPositionIdObj[0]['id'];

					$result = GlassFeeding_Model::where('id', $glassFeedId);
					$input['stage'] = $nextStage;
					$input['status'] = 0;
					$input['appr_process'] = 1;
					$res = $result->update($input);
				} else {
					$result = GlassFeeding_Model::where('id', $glassFeedId);
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

				$result = GlassFeeding_Model::where('id', $glassFeedId);
				$input['status'] = request()->input('ApprStat');
				$input['appr_process'] = 1;
				$res = $result->update($input);
			}

			if ($res == 1) {
				$data = array(
					'id'              => time(),
					'glassFeedId'     => $glassFeedId,
					'remarks'         => $remarks,
					'action'          => $ActionString,
					'actionBy'        => request()->session()->get('empId'),
					'ip'              => $this->getUserIP(),
				);

				$res = GlassFeedingHist_Model::create($data);

				return redirect()->to(url('production-lineup/glass-feeding-approval-list'))->with('success', 'Aprroval Action done Successfully');
			} else {
				return redirect()->to(url('production-lineup/glass-feeding-approval-list'))->with('failed', 'Something went wrong contact with System Aministrator');
			}
		} else {
			return redirect()->to(url(''))->with('authErr', 'Youare not properly logged in.');
		}
	}

	public function formUpdateView($id)
	{
		if (request()->session()->has('empId')) {
			$data['menu'] = 'glass-feeding';
			$data['userList'] = DB::table('mstr_emp')
				->select('mstr_emp.*')
				->where('mstr_emp.status', '1')
				->get();
			$data['ShiftMaster'] = DB::table('hr_mstr_shift')
				->select('hr_mstr_shift.*')
				->get();

			$data['materialMaster'] = DB::table('tbl_factory_material_master_laravel')
				->select('id', 'title')
				->where('id', 3) // Changed to Glass material ID (assuming 3 is for Glass)
				->get();

			// Modified query to get materials correctly for Glass Feeding
			$data['glassFeedingMaterials'] = DB::table('tbl_factory_glass_feed_material_laravel as gfml')
				->select(
					'gfml.id',
					'gfml.material as mat_id',
					'gfml.size',
					'gfml.time',
					'gfml.productionQty',
					'gfml.RejectQty',
					'gfml.reason',
					'gfml.defectCat',
					'mml.title as material_name'
				)
				->leftJoin('tbl_factory_material_master_laravel as mml', 'gfml.material', '=', 'mml.id')
				->where('gfml.glassFeedId', $id)
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
			$data['GlassSize'] = DB::table('master_type_dtls')
				->select('master_type_dtls.*')
				->where('master_type_dtls.parent_id', 47) // Assuming 87 is for Glass Size
				->get();

			$data['glassFeedingDetails'] = GlassFeeding_Model::find($id);

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
				->where('psml.material', 3)
			    ->where('psl.status', 1)
				->get();

			$data['batchData'] = DB::table('tbl_factory_production_setup_laravel as psl')
				->select(
					'psl.*',
					'psml.size AS efficiency',
					'psml.brand',
					'prj_material.material_name as matname'
				)
				->leftJoin('materialmanagement_add_material', 'materialmanagement_add_material.id', '=', 'psl.finishGood')
				->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
				->join('tbl_factory_production_setup_material_laravel as psml', function ($join) {
					$join->on('psml.batchNo', '=', 'psl.batchNo')
						->where('psml.material', '=', 3); // Changed to Glass material ID
				})
				->join('tbl_factory_glass_feed_laravel as gfl', 'psl.batchNo', '=', 'gfl.batchNo')
				->orderBy('psl.created_at', 'DESC')
				->where('gfl.id', $id)
				->first();

            $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
			return view('ProductionLineUp.GlassFeeding.glass-feeding-update', $data);
		} else {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}
	}

	public function updateGlassFeeding(Request $request, $id)
	{
		if (!$request->session()->has('empId')) {
			return redirect()->to(url(''))->with('authErr', 'You are not properly logged in.');
		}

		// Get the approval stage for Glass Feeding (module ID: 1753341338)
		$stage = ApprovalStage_Model::where('stage_module', '1753341338')
			->where('stage_stat', '1')
			->orderBy('id', 'asc')
			->first();

		$data = [
			'date'       => $request->date ?? null,
			'shift'      => $request->shift ?? '',
			'plant'      => $request->plant_no ?? '',
			'operator'   => $request->operator ?? null,
			'checker'    => $request->checker ?? null,
			'status'     => 0,
			'stage'      => $stage ? $stage->id : null,
			'appr_process' => 0,
			'updated_at' => now(),
		];

		if (GlassFeeding_Model::where('id', $id)->update($data)) {
			if ($request->has('materials')) {
				$materialIds = [];

				foreach ($request->materials as $material) {
					if (isset($material['id']) && !empty($material['id'])) {
						// Update existing material
						GlassFeedingMaterial_Model::where('id', $material['id'])->update([
							'material'      => $material['name'],
							'size'          => $material['size'],
							'time'          => $material['time'],
							'productionQty' => $material['production_qty'],
							'RejectQty'     => $material['rejection_qty'],
							'reason'        => $material['stage'],
							'defectCat'     => $material['defect_category']
						]);
						$materialIds[] = $material['id'];
					} else {
						// Create new material
						$newMaterial = GlassFeedingMaterial_Model::create([
							'glassFeedId'   => $id,
							'material'      => $material['name'],
							'size'          => $material['size'],
							'time'          => $material['time'],
							'productionQty' => $material['production_qty'],
							'RejectQty'     => $material['rejection_qty'],
							'reason'        => $material['stage'],
							'defectCat'     => $material['defect_category']
						]);
						$materialIds[] = $newMaterial->id;
					}
				}

				// Remove deleted materials
				GlassFeedingMaterial_Model::where('glassFeedId', $id)
					->whereNotIn('id', $materialIds)
					->delete();
			}

			// Log history
			$logData = [
				'id'          => time(),
				'glassFeedId' => $id,
				'remarks'     => 'Verified',
				'action'      => 'Verified',
				'actionBy'    => $request->session()->get('empId'),
				'ip'          => $this->getUserIP(),
				'created_at'  => now(),
				'updated_at'  => now(),
			];
			GlassFeedingHist_Model::create($logData);

			return redirect()->to(url('production-lineup/glass-feeding'))
				->with('success', 'Glass Feeding Request Updated Successfully');
		}

		return redirect()->to(url('production-lineup/glass-feeding'))
			->with('failed', 'Glass Feeding Request not found');
	}
}
