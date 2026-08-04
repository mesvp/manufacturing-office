<?php

namespace App\Http\Controllers\ProductionLineUp\NinetyDegQC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{NinetyDeg_Model, NinetyDeg_Model_RWRK, NinetyDegDamage_Model, NinetyDegDamage_Model_RWRK, NinetyDegHist_Model};
use App\Models\ProductionLineUp\{EL_QC, EL_QC_Defect, EL_QC_RWRK, EL_QC_Defect_RWRK, EL_QC_History};
use App\Models\ProductionLineUp\{Bushing_Model, BushingMaterial_Model, BushingDamageMaterial_Model};
use App\Models\ProductionLineUp\{ProductSetUpMaterial_Model, Raw_Consumption_Transac_Model};

class NinetyDeg_Controller extends Controller
{
    public  static function PermittedMenuList($sessionId)
    {
        //Menu Permission
        $res = DB::table('prod_menu_laravel')
            ->leftJoin('prod_menu_acc_laravel', 'prod_menu_laravel.id', '=', 'prod_menu_acc_laravel.menu_id')
            ->where('prod_menu_acc_laravel.emp_id', '=', $sessionId)
            ->where('prod_menu_acc_laravel.accessType', '=', 'yes')
            ->select('prod_menu_laravel.*', 'prod_menu_acc_laravel.accessType')
            ->get();

        return $res;
    }
    //
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

    public function index(Request $request)
    {
        $data['menu'] = '90deg-qc';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        $query = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select([
                'elqc.*',
                'ninetydeg.ninetydeg_id',
                'ninetydeg.status as sts',
                'ninetydeg.rwrk_status as rwsts',
                'ninetydeg.ninetydeg_source',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as elqc_operator',
                'b.fullname as elqc_incharge',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_ninetydeg_laravel as ninetydeg', 'ninetydeg.ninetydeg_barcode', '=', 'elqc.elqc_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'elqc.elqc_batchNo')
            ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

        // 2. Apply the complex mandatory condition
        $query->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNull('ninetydeg.ninetydeg_laminatorNo')
                    ->where('elqc.status', 1);
            })->orWhere(function ($sub) {
                $sub->where('ninetydeg.rwrk_status', '')
                    ->where('ninetydeg.status', 0);
            });
        });

        // 3. Apply dynamic filters from Request
        if ($request->filled('createdBy')) {
            $query->where('elqc.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('elqc.elqc_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('elqc.elqc_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('elqc.elqc_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('elqc.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('elqc.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('elqc.elqc_batchNo', $request->batchNo);
        }

        // 4. Finalize with ordering and pagination
        $data['AllLists'] = $query->orderBy('elqc.created_at', 'DESC')->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.index', $data);
    }

    public function passedList(Request $request)
    {
        $data['menu'] = '90deg-qc';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        // 1. Initialize the query builder
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator-name',
                'b.fullname as ninetydeg_incharge-name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 1);

        // 3. Finalize with Grouping, Ordering, and Pagination
        $data['AllLaminatorLists'] = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.passed', $data);
    }

    public function rejectedList(Request $request)
    {
        $data['menu'] = '90deg-qc';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        // 1. Start the query builder
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator-name',
                'b.fullname as ninetydeg_incharge-name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 2);
        // 3. Finalize with Grouping, Ordering, and Pagination
        $data['AllLaminatorLists'] = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->paginate(10);
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.rejected', $data);
    }

    public function indexAll(Request $request)
    {
        $data['menu'] = '90deg-qc-all';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        $query = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select([
                'elqc.*',
                'ninetydeg.ninetydeg_id',
                'ninetydeg.status as sts',
                'ninetydeg.rwrk_status as rwsts',
                'ninetydeg.ninetydeg_source',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as elqc_operator',
                'b.fullname as elqc_incharge',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_ninetydeg_laravel as ninetydeg', 'ninetydeg.ninetydeg_barcode', '=', 'elqc.elqc_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'elqc.elqc_batchNo')
            ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

        // 2. Apply the complex mandatory condition
        $query->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNull('ninetydeg.ninetydeg_laminatorNo')
                    ->where('elqc.status', 1);
            })->orWhere(function ($sub) {
                $sub->where('ninetydeg.rwrk_status', '')
                    ->where('ninetydeg.status', 0);
            });
        });

        // 3. Apply dynamic filters from Request
        if ($request->filled('createdBy')) {
            $query->where('elqc.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('elqc.elqc_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('elqc.elqc_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('elqc.elqc_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('elqc.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('elqc.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('elqc.elqc_batchNo', $request->batchNo);
        }

        // 4. Finalize with ordering and pagination
        $data['AllLists'] = $query->orderBy('elqc.created_at', 'DESC')->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.index-all', $data);
    }

    public function passedListAll(Request $request)
    {
        $data['menu'] = '90deg-qc-all';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        // 1. Initialize the query builder
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator-name',
                'b.fullname as ninetydeg_incharge-name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 1);

        // 3. Finalize with Grouping, Ordering, and Pagination
        $data['AllLaminatorLists'] = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.passed-all', $data);
    }

    public function rejectedListAll(Request $request)
    {
        $data['menu'] = '90deg-qc-all';

        $data['ShiftMaster'] = DB::table('hr_mstr_shift')
            ->select('hr_mstr_shift.*')
            ->get();

        $data['userList'] = DB::table('mstr_emp')
            ->select('mstr_emp.id', 'mstr_emp.fullname')
            ->where('mstr_emp.status', '1')
            ->get();

        $data['batchList'] = DB::table('tbl_factory_bushing_laravel')
            ->select('tbl_factory_bushing_laravel.bushing_batchNo')
            ->groupBy('tbl_factory_bushing_laravel.bushing_batchNo')
            ->get();

        // 1. Start the query builder
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator-name',
                'b.fullname as ninetydeg_incharge-name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 2);
        // 3. Finalize with Grouping, Ordering, and Pagination
        $data['AllLaminatorLists'] = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->paginate(10);
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.NinetyDeg.rejected-all', $data);
    }


    public function pendingExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $query = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select([
                'elqc.*',
                'ninetydeg.ninetydeg_id',
                'ninetydeg.status as sts',
                'ninetydeg.rwrk_status as rwsts',
                'ninetydeg.ninetydeg_source',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as elqc_operator_name',
                'b.fullname as elqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_ninetydeg_laravel as ninetydeg', 'ninetydeg.ninetydeg_barcode', '=', 'elqc.elqc_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'elqc.elqc_batchNo')
            ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

        // 2. Apply the complex mandatory condition
        $query->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNull('ninetydeg.ninetydeg_laminatorNo')
                    ->where('elqc.status', 1);
            })->orWhere(function ($sub) {
                $sub->where('ninetydeg.rwrk_status', '')
                    ->where('ninetydeg.status', 0);
            });
        });

        // 3. Apply dynamic filters from Request
        if ($request->filled('createdBy')) {
            $query->where('elqc.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('elqc.elqc_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('elqc.elqc_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('elqc.elqc_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('elqc.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('elqc.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('elqc.elqc_batchNo', $request->batchNo);
        }

        // 4. Finalize with ordering
        $AllLists = $query->orderBy('elqc.created_at', 'DESC')->get();


        $fileName = 'pending_ELQC_report_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt',  'Bus Bar', 'Operator', 'Incharge']; //'Cell Efficiency',

        // 4. Create a callback to stream the data
        $callback = function () use ($AllLists, $columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows
            foreach ($AllLists as $key => $row) {
                $sl = $key + 1;
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->elqc_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->elqc_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->elqc_barcode,
                    $row->ninetydeg_source ?? 'Layout',
                    $row->wattage,
                    //$row->cellSize,
                    $row->bus_bar ?? '-',
                    $row->elqc_operator_name,
                    $row->elqc_incharge_name,
                ]);
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }

    public function passedExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator_name',
                'b.fullname as ninetydeg_incharge_name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 1);

        // 3. Finalize with Grouping, Ordering, and Pagination
        $AllLists = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->get();


        $fileName = 'passed_ELQC_report_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt', 'Cell Efficiency', 'Bus Bar', 'Operator', 'Incharge'];

        // 4. Create a callback to stream the data
        $callback = function () use ($AllLists, $columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows

            foreach ($AllLists as $key => $row) {
                $sl = $key + 1;
                //if ($row->status == '1' && $row->rwrk_status == '1'){
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->ninetydeg_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->ninetydeg_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->ninetydeg_barcode,
                    $row->ninetydeg_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize ?? '-',
                    $row->bus_bar ?? '-',
                    $row->ninetydeg_operator_name,
                    $row->ninetydeg_incharge_name,
                ]);
                //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }

    public function rejectedExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
            ->selectRaw('SUM(cell_qty)')
            ->whereColumn('d2.elqcId', 'elqc.elqc_id');

        // 2. Build the main query
        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator_name',
                'b.fullname as ninetydeg_incharge_name',
                'c.fullname as createdBy',
            ])
            // Subquery for cell damage count
            ->selectSub(function ($query) {
                $query->from('tbl_factory_ninetydeg_defect_laravel as d2')
                    ->selectRaw('SUM(cell_qty)')
                    ->whereColumn('d2.ninetydeg_Id', 'ninetydeg.ninetydeg_id');
            }, 'no_of_cell_damage')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id');

        // 2. Add Conditional Filters (Laravel handles the sanitization)
        if ($request->filled('createdBy')) {
            $query->where('ninetydeg.created_by', $request->createdBy);
        }
        if ($request->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', $request->operator);
        }
        if ($request->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', $request->checker);
        }
        if ($request->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', $request->shift);
        }
        if ($request->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', $request->toDate);
        }
        if ($request->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', $request->batchNo);
        }

        $query->where('ninetydeg.status', 2);

        // 3. Finalize with Grouping, Ordering, and Pagination
        $AllLists = $query->groupBy('ninetydeg.ninetydeg_id')
            ->orderBy('ninetydeg.created_at', 'DESC')
            ->get();

        $fileName = 'rejected_ELQC_report_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt', 'Cell Efficiency', 'Bus Bar', 'Operator', 'Incharge'];

        // 4. Create a callback to stream the data
        $callback = function () use ($AllLists, $columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows
            foreach ($AllLists as $key => $row) {
                $sl = $key + 1;
                //if ($row->status == '2' && $row->rwrk_status == '2'){
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->ninetydeg_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->ninetydeg_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->ninetydeg_barcode,
                    $row->ninetydeg_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize ?? '-',
                    $row->bus_bar ?? '-',
                    $row->ninetydeg_operator_name,
                    $row->ninetydeg_incharge_name,
                ]);
                //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }



    public function add_qc()
    {
        $data['menu'] = '90deg-qc';
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
            ->where('master_type_dtls.parent_id', 88)
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
        return view('ProductionLineUp.NinetyDeg.add_qc', $data);
    }


    public function getLaminatorId(Request $request)
    {
        $batchNo = $request->q;

        $sql = "SELECT 
            elqc.elqc_id
            FROM tbl_factory_el_qc_laravel AS elqc
            LEFT JOIN tbl_factory_ninetydeg_laravel AS ninetydeg
                ON ninetydeg.ninetydeg_laminatorNo = elqc.elqc_id
            WHERE ninetydeg.ninetydeg_laminatorNo IS NULL AND elqc.elqc_batchNo = $batchNo
            ORDER BY elqc.created_at DESC";

        $elqcData = DB::select($sql);;

        if ($elqcData) {
            return response()->json(['elqc_ids' => $elqcData]);
        } else {
            return response()->json(['elqc_ids' => null]);
        }
    }


    public function getBushingMaterial(Request $request)
    {
        $batchno = $request->input('q');
        // $batchno = DB::table('tbl_factory_bushing_laravel')
        //     ->where('bushing_id', $bushingno)
        //     ->value('bushing_batchNo');
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

    public function validateBarCode(Request $request)
    {
        $barcode = $request->get('barCode');
        $btch_viewNo = $request->get('id');
        $action = $request->get('action') ?? '';
        $batchNo = $request->get('id') ?? '';

        if ($action === 'view') {
            $exists = DB::table('tbl_factory_bushing_laravel')
                ->select('bushing_logo')
                ->where('bushing_barCode', $barcode)
                ->where('bushing_batchNo', $btch_viewNo)
                //->where('bushing_id', $batchNo)
                ->first();
        } else {
            $exists = DB::table('tbl_factory_bushing_laravel')
                ->select('bushing_id', 'bushing_logo')
                ->where('bushing_barCode', $barcode)
                ->where('bushing_batchNo', $batchNo)
                ->get();
        }

        // If action is 'view', we're in view mode - skip EL QC validation
        if ($action === 'view') {
            return response()->json([
                'status' => 'success',
                'bushing_logo' => $exists->bushing_logo ?? null,
                'message' => 'Barcode is valid (view mode).',
            ]);
        }

        // Check if barcode passed in 90Deg QC or Not
        $passExists = DB::table('tbl_factory_el_qc_laravel')
            ->where('elqc_barcode', $barcode)
            ->where('elqc_batchNo', $batchNo)
            ->exists();

        // Check if barcode already used in JB
        $elQcExists = DB::table('tbl_factory_ninetydeg_laravel')
            ->where('ninetydeg_barcode', $barcode)
            ->where('ninetydeg_batchNo', $batchNo)
            ->where(function ($query) {
                $query->where('status', '!=', 0)
                    ->orWhere('rwrk_status', '!=', '');
            })
            ->exists();

        // If found in EL QC - INVALID
        if ($elQcExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in Ninetydegree QC.',
            ]);
        }
        if (!$passExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not passed ELQC against this batchno.',
            ]);
        }
        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'bushing_id' => $exists[0]->bushing_id ?? null,
                'bushing_logo' => $exists[0]->bushing_logo,
                'message' => 'Barcode is valid.',
            ]);
        }

        return response()->json(['exists' => $exists]);
    }


    public function insert(Request $request)
    {
        if ($request->input('err') == '1') {
            return redirect()->back()->with('error', 'Please correct the errors before submitting the form.');
        } else {

            $demoId = date('YmdHis');


            $barCode = $request->input('barCode');

            // Check if valid serial number exists
            $bExists = DB::table('factory_serial_number_details')
                ->leftJoin('factory_serial_numbers as sl', 'factory_serial_number_details.sl_id', '=', 'sl.id')
                ->where('sl.Approve_status', 'APPROVE')
                ->whereNull('factory_serial_number_details.status')
                ->where('factory_serial_number_details.sl_no', $barCode)
                ->exists();


            if ($bExists) {

                //Bushing Auto Entry
                $exists = DB::table('tbl_factory_bushing_laravel')
                    ->where('bushing_barCode', request()->input('barCode'))
                    ->exists();
                if ($exists == false) {
                    $data = array(
                        'bushing_id' => $demoId,
                        'bushing_date' => date('d-m-Y'),
                        'bushing_time' => date('H:i:s'),
                        'bushing_operator' => request()->input('operator'),
                        'bushing_batchNo' => request()->input('batchNo'),
                        'bushing_incherge' => request()->input('incharge'),
                        'bushing_shift' => request()->input('shift'),
                        'bushing_plant' => request()->input('plant'),
                        'bushing_logo' => 'Yes',
                        'bushing_hasDamage' => 'No',
                        'bushing_rfid' => request()->input('rfid'),
                        'bushing_barCode' => request()->input('barCode'),
                        'created_by' => request()->session()->get('empId')
                    );

                    $res = Bushing_Model::create($data);

                    $materials = ProductSetUpMaterial_Model::where('batchNo', request()->input('batchNo'))->get();

                    foreach ($materials as $material) {
                        $data = array(
                            'bushingId' => $demoId,
                            'prd_matId' => $material['material'],
                            'status' => 'Yes'
                        );

                        BushingMaterial_Model::create($data);
                    }
                }

                //REWORK
                $exists = DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_barcode', request()->input('barCode'))
                    ->where('status', '<>', '1')
                    ->exists();
                if ($exists == true) {
                    $qry = EL_QC::where('elqc_barcode', $request->input('barCode'));
                    $data = array(
                        'status'        => '1',
                        'rwrk_status'   => '1'
                    );

                    $res =  $qry->update($data);
                }

                //Normal
                $exists = DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_barcode', request()->input('barCode'))
                    ->exists();
                if ($exists == false) {

                    $data = array(
                        'elqc_id'       => $demoId,
                        'elqc_date'     => date('d-m-Y'),
                        'elqc_time'     => date('H:i:s'),
                        'elqc_operator' => $request->input('operator'),
                        'elqc_source'   => 'Layup',
                        'elqc_bushingNo' => $demoId,
                        'elqc_batchNo'  => $request->input('batchNo'),
                        'elqc_incharge' => $request->input('incharge'),
                        'elqc_shift'    => $request->input('shift'),
                        'elqc_plant'    => $request->input('plant'),
                        'status'        => '1',
                        'rwrk_status'   => '0',
                        'elqc_rfid'     => $request->input('rfid'),
                        'elqc_barcode'  => $request->input('barCode'),
                        'created_by'    => $request->session()->get('empId')
                    );

                    $res = EL_QC::create($data);

                    EL_QC_History::create([
                        'el_qc_id'   => $demoId,
                        'action'     => 'Raised',
                        'ip_address' => $this->getUserIP(),
                        'created_by' => $request->session()->get('empId')
                    ]);
                }
            }



            // Check if valid serial number exists
            $bExists = DB::table('factory_serial_number_details')
                ->leftJoin('factory_serial_numbers as sl', 'factory_serial_number_details.sl_id', '=', 'sl.id')
                ->where('sl.Approve_status', 'APPROVE')
                ->whereNull('factory_serial_number_details.status')
                ->where('factory_serial_number_details.sl_no', $barCode)
                ->exists();

            //$Bexists = true;
            $PreExists = true;

            if ($bExists && $PreExists) {

                $exists = DB::table('tbl_factory_ninetydeg_laravel')
                    ->where('ninetydeg_barcode', $request->input('barCode'))
                    ->exists();

                $validBarcode = DB::table('tbl_factory_ninetydeg_laravel')
                    ->select('*')
                    ->where('ninetydeg_barcode', $request->input('barCode'))
                    ->where('status', '0')
                    ->where('rwrk_status', '')
                    ->first();

                if ($exists == false) {

                    $qcId = DB::table('tbl_factory_el_qc_laravel')
                        ->where('elqc_barcode', $request->input('barCode'))
                        ->where('elqc_batchNo', $request->input('batchNo'))
                        ->value('elqc_id');

                    $id = date('YmdHis');
                    $data = array(
                        'ninetydeg_id'          => $id,
                        'ninetydeg_date'        => date('d-m-Y'),
                        'ninetydeg_time'        => date('H:i:s'),
                        'ninetydeg_operator'    => $request->input('operator'),
                        'ninetydeg_source'      => 'ELQC',
                        'ninetydeg_laminatorNo' => $qcId,
                        'ninetydeg_batchNo'     => $request->input('batchNo'),
                        'ninetydeg_incharge'    => $request->input('incharge'),
                        'ninetydeg_cycle_no'    => $request->input('cycleNo'),
                        'ninetydeg_shift'       => $request->input('shift'),
                        'ninetydeg_plant'       => $request->input('plant'),
                        'status'                => $request->input('el_type'),
                        'rwrk_status'           => '0',
                        'ninetydeg_pDefectRsn'  => $request->input('p_reject_reason'),
                        'ninetydeg_rfid'        => $request->input('rfid'),
                        'ninetydeg_barcode'     => $request->input('barCode'),
                        'scan_flag' => 1,
                        'created_by'            => $request->session()->get('empId')
                    );

                    $res = NinetyDeg_Model::create($data);

                    NinetyDegHist_Model::create([
                        'ninetydeg_id' => $id,
                        'action'       => 'Raised',
                        'ip_address'   => $this->getUserIP(),
                        'created_by'   => auth()->id()
                    ]);

                    $cell_positions    = $request->input('cell_position', []);
                    $cell_qtys         = $request->input('cell_qty', []);
                    $dmgMat_reasons    = $request->input('dmgMat_reason', []);
                    $defect_categories = $request->input('dmgMat_cat', []);
                    $res_prsns         = $request->input('res_prsn', []);
                    $res_machines      = $request->input('res_machine', []);

                    if ($request->input('el_type') === '0' && is_array($cell_positions) && count($cell_positions) > 0) {
                        foreach ($cell_positions as $i => $cell_no) {
                            if ($cell_no === null || $cell_no === '') {
                                continue;
                            }

                            NinetyDegDamage_Model::create([
                                'ninetydeg_Id' => $id,
                                'cell_no'      => $cell_no,
                                'cell_qty'     => $cell_qtys[$i] ?? null,
                                'defectRsn'    => $dmgMat_reasons[$i] ?? null,
                                'defectCatgry' => $defect_categories[$i] ?? null,
                                'res_prsn'     => $res_prsns[$i] ?? null,
                                'res_machine'  => $res_machines[$i] ?? null,
                            ]);
                        }
                    }

                    // RAW MATERIAL CONSUMPTION

                    if ($request->input('el_type') != 1) {

                        $batchNoGetBom = $request->input('batchNo');
                        $transacCat = 'Material Consumption due to reject in Ninetydegree QC';

                        $date = date('Y/m/d');

                        $batchMats = DB::table('tbl_factory_production_setup_material_laravel')
                            ->select('bomMat', 'bomQty')
                            ->where('batchNo', '=', $batchNoGetBom) // Fixed quotes
                            ->get();

                        foreach ($batchMats as $batchMat) {

                            DB::table('master_raw_material')
                                ->where('Organization', 4)
                                ->where('Godown_Name', 60)
                                ->where('Material', $batchMat->bomMat)
                                ->decrement('Quantity', $batchMat->bomQty);

                            $data = array(
                                'matrerial'         => $batchMat->bomMat,
                                'date'              => $date,
                                'batch'             => $batchNoGetBom,
                                'qty'               => $batchMat->bomQty,
                                'godown'            => 60,
                                'organisation'      => 4,
                                'refNo'             => $id,
                                'transacCategory'   => $transacCat,
                                'raisedBy'          => $request->session()->get('empId'),
                                'ip'                => $this->getUserIP()
                            );

                            Raw_Consumption_Transac_Model::create($data);
                        }
                    }

                    // RAW MATERIAL CONSUMPTION 

                    $lock     = request()->input('lock');
                    $batchNo  = request()->input('batchNo');
                    $oprtr    = request()->input('operator');
                    $incherge = request()->input('incharge');
                    $shift    = request()->input('shift');
                    $plant    = request()->input('plant');
                    $page     = request()->input('page');

                    if ($res->exists) {
                        if ($lock && $page) {
                            $url = 'production-lineup/90deg-qc/add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                            return redirect($url)->with('success', '90deg-qc data stored successfully!');
                        } else {
                            return redirect('production-lineup/90deg-qc')->with('success', '90deg-qc data stored successfully!');
                        }
                    }
                } elseif ($exists == true && count((array)$validBarcode) > 0) {

                    DB::transaction(function () use ($request) {

                        // ── Step 1: Fetch the existing NinetyDeg record ──────────────────────
                        $existingQC = NinetyDeg_Model::where('ninetydeg_barcode', $request->input('barCode'))->first();

                        if ($existingQC) {

                            // ── Step 2: Copy NinetyDeg_Model → NinetyDeg_Model_RWRK ──────────
                            NinetyDeg_Model_RWRK::create($existingQC->toArray());

                            // ── Step 3: Fetch & copy NinetyDegDamage_Model → NinetyDegDamage_Model_RWRK ──
                            $existingDefects = NinetyDegDamage_Model::where('ninetydeg_Id', $existingQC->ninetydeg_id)->get();

                            foreach ($existingDefects as $defect) {
                                NinetyDegDamage_Model_RWRK::create($defect->toArray());
                            }

                            // ── Step 4: Delete original NinetyDegDamage_Model records ─────────
                            NinetyDegDamage_Model::where('ninetydeg_Id', $existingQC->ninetydeg_id)->delete();

                            // ── Step 5: Delete original NinetyDeg_Model record ────────────────
                            NinetyDeg_Model::where('ninetydeg_id', $existingQC->ninetydeg_id)->delete();
                        }

                        // ── Step 6: Prepare fresh NinetyDeg record ───────────────────────────
                        $qcId = DB::table('tbl_factory_el_qc_laravel')
                            ->where('elqc_barcode', $request->input('barCode'))
                            ->where('elqc_batchNo', $request->input('batchNo'))
                            ->value('elqc_id');

                        $newId = date('YmdHis');

                        $newData = array(
                            'ninetydeg_id'          => $newId,
                            'ninetydeg_date'        => date('d-m-Y'),
                            'ninetydeg_time'        => date('H:i:s'),
                            'ninetydeg_operator'    => $request->input('operator'),
                            'ninetydeg_source'      => 'ELQC',
                            'ninetydeg_laminatorNo' => $qcId,
                            'ninetydeg_batchNo'     => $request->input('batchNo'),
                            'ninetydeg_incharge'    => $request->input('incharge'),
                            'ninetydeg_cycle_no'    => $request->input('cycleNo'),
                            'ninetydeg_shift'       => $request->input('shift'),
                            'ninetydeg_plant'       => $request->input('plant'),
                            'status'                => $request->input('el_type'),
                            'rwrk_status'           => '1',
                            'ninetydeg_pDefectRsn'  => $request->input('p_reject_reason'),
                            'ninetydeg_rfid'        => $request->input('rfid'),
                            'ninetydeg_barcode'     => $request->input('barCode'),
                            'created_by'            => $request->session()->get('empId')
                        );

                        // ── Step 7: Insert fresh NinetyDeg record ────────────────────────────
                        NinetyDeg_Model::create($newData);

                        NinetyDegHist_Model::create([
                            'ninetydeg_id' => $newId,
                            'action'       => 'Rework',
                            'ip_address'   => $this->getUserIP(),
                            'created_by'   => auth()->id()
                        ]);

                        // ── Step 8: Insert fresh NinetyDegDamage records ─────────────────────
                        $cell_positions    = $request->input('cell_position', []);
                        $cell_qtys         = $request->input('cell_qty', []);
                        $dmgMat_reasons    = $request->input('dmgMat_reason', []);
                        $defect_categories = $request->input('dmgMat_cat', []);
                        $res_prsns         = $request->input('res_prsn', []);
                        $res_machines      = $request->input('res_machine', []);

                        if ($request->input('el_type') === '0' && is_array($cell_positions) && count($cell_positions) > 0) {
                            foreach ($cell_positions as $i => $cell_no) {
                                if ($cell_no === null || $cell_no === '') {
                                    continue;
                                }

                                NinetyDegDamage_Model::create([
                                    'ninetydeg_Id' => $newId,
                                    'cell_no'      => $cell_no,
                                    'cell_qty'     => $cell_qtys[$i] ?? null,
                                    'defectRsn'    => $dmgMat_reasons[$i] ?? null,
                                    'defectCatgry' => $defect_categories[$i] ?? null,
                                    'res_prsn'     => $res_prsns[$i] ?? null,
                                    'res_machine'  => $res_machines[$i] ?? null,
                                ]);
                            }
                        }
                    });

                    return redirect('production-lineup/90deg-qc')->with('success', '90deg-qc rework data stored successfully!');
                } else {
                    return redirect('production-lineup/90deg-qc')->with('success', '90deg-qc data store failed! Duplicate Barcode.');
                }
            } else {
                return redirect('production-lineup/90deg-qc')->with('success', '90deg-qc data store failed! This Barcode not Valid or already Used');
            }
        }
    }
    public function damage_report()
    {
        $data['menu'] = '90deg-qc-damage-report';

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
        return view('ProductionLineUp.NinetyDeg.damage', $data);
    }


    public function view_90deg_qc($id = null)
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
        return view('ProductionLineUp.NinetyDeg.view_90deg_qc', $data);
    }

    public function fetchRFIDBar(Request $request)
    {
        $batchNo = $request->input('batch_No');
        $elqcNo = $request->input('elqcNo');

        $RFIDBardtls = DB::table('tbl_factory_el_qc_laravel as bol')
            ->select('bol.elqc_rfid', 'bol.elqc_barcode')
            ->where('bol.elqc_batchNo', $batchNo)
            ->where('bol.elqc_id', $elqcNo)
            ->first();

        return response()->json(['rfid' => $RFIDBardtls->elqc_rfid, 'barcode' => $RFIDBardtls->elqc_barcode]);
    }
}
