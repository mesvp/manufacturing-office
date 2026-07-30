<?php

namespace App\Http\Controllers\ProductionLineUp\FinalQC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{FinalQC_Model, FinalQC_Defect_Model, FinalQC_Hist_Model};
use App\Models\Production\{Production, ProductionBatch, ProductionData};
use App\Models\ProductionLineUp\{JB_Model, JB_Damage_Model, JB_Hist_Model};
use App\Models\ProductionLineUp\{NinetyDeg_Model, NinetyDeg_Model_RWRK, NinetyDegDamage_Model, NinetyDegDamage_Model_RWRK, NinetyDegHist_Model};
use App\Models\ProductionLineUp\{EL_QC, EL_QC_Defect, EL_QC_RWRK, EL_QC_Defect_RWRK, EL_QC_History};
use App\Models\ProductionLineUp\{Bushing_Model, BushingMaterial_Model, BushingDamageMaterial_Model};
use App\Models\ProductionLineUp\{ProductSetUpMaterial_Model};


class FinalQC_Controller extends Controller
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
        $data['menu'] = 'final-qc';

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

        $query = DB::table('tbl_factory_jb_laravel as jb')
            ->select([
                'jb.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as jb_operator_name',
                'b.fullname as jb_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_fqc_laravel as fqc', 'fqc.fqc_QC', '=', 'jb.jb_id')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id')
            ->whereNull('fqc.fqc_QC')
            ->where('jb.status', 1);

        // 2. Dynamically apply filters safely (using bindings automatically)
        if ($request->filled('createdBy')) {
            $query->where('jb.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('jb.jb_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('jb.jb_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('jb.jb_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(jb.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(jb.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('jb.jb_batchNo', $request->input('batchNo'));
        }

        // 3. Apply Grouping, Ordering, and Pagination
        // Adjust pagination number (e.g., 15) to whatever your UI needs
        $data['AllLists'] = $query->groupBy('jb.jb_barcode')
            ->orderBy('jb.created_at', 'desc')
            ->paginate(15);


        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.index', $data);
    }
    public function passedList(Request $request)
    {
        $data['menu'] = 'final-qc';

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


        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', 1);

        // 2. If your initial $Condition string had other default conditions, add them here.
        // e.g., $query->where('some_col', 'some_val');

        // 3. Dynamically apply filters safely using Laravel's Request
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->input('batchNo'));
        }

        // 4. Apply Grouping, Ordering, and Pagination
        // Change '15' to whatever number of items you want per page
        $data['AllLaminatorLists'] = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'desc')
            ->paginate(15);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.passed', $data);
    }
    public function rejectedList(Request $request)
    {
        $data['menu'] = 'final-qc';

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

        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', '<>', 1);

        // 2. If your initial $Condition string had other default conditions, add them here.
        // e.g., $query->where('some_col', 'some_val');

        // 3. Dynamically apply filters safely using Laravel's Request
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->input('batchNo'));
        }

        // 4. Apply Grouping, Ordering, and Pagination
        // Change '15' to whatever number of items you want per page
        $data['AllLaminatorLists'] = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'desc')
            ->paginate(15);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.rejected', $data);
    }



    public function indexAll(Request $request)
    {
        $data['menu'] = 'final-qc-all';

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

        $query = DB::table('tbl_factory_jb_laravel as jb')
            ->select([
                'jb.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as jb_operator_name',
                'b.fullname as jb_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_fqc_laravel as fqc', 'fqc.fqc_QC', '=', 'jb.jb_id')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id')
            ->whereNull('fqc.fqc_QC')
            ->where('jb.status', 1);

        // 2. Dynamically apply filters safely (using bindings automatically)
        if ($request->filled('createdBy')) {
            $query->where('jb.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('jb.jb_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('jb.jb_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('jb.jb_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(jb.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(jb.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('jb.jb_batchNo', $request->input('batchNo'));
        }

        // 3. Apply Grouping, Ordering, and Pagination
        // Adjust pagination number (e.g., 15) to whatever your UI needs
        $data['AllLists'] = $query->groupBy('jb.jb_barcode')
            ->orderBy('jb.created_at', 'desc')
            ->paginate(15);


        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.index-all', $data);
    }
    public function passedListAll(Request $request)
    {
        $data['menu'] = 'final-qc-all';

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


        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', 1);

        // 2. If your initial $Condition string had other default conditions, add them here.
        // e.g., $query->where('some_col', 'some_val');

        // 3. Dynamically apply filters safely using Laravel's Request
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->input('batchNo'));
        }

        // 4. Apply Grouping, Ordering, and Pagination
        // Change '15' to whatever number of items you want per page
        $data['AllLaminatorLists'] = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'desc')
            ->paginate(15);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.passed-all', $data);
    }
    public function rejectedListAll(Request $request)
    {
        $data['menu'] = 'final-qc-all';

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

        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', '<>', 1);

        // 2. If your initial $Condition string had other default conditions, add them here.
        // e.g., $query->where('some_col', 'some_val');

        // 3. Dynamically apply filters safely using Laravel's Request
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->input('createdBy'));
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->input('operator'));
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->input('checker'));
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->input('shift'));
        }

        if ($request->filled('fromDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) >= ?", [$request->input('fromDate')]);
        }

        if ($request->filled('toDate')) {
            $query->whereRaw("CAST(fqc.created_at AS DATE) <= ?", [$request->input('toDate')]);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->input('batchNo'));
        }

        // 4. Apply Grouping, Ordering, and Pagination
        // Change '15' to whatever number of items you want per page
        $data['AllLaminatorLists'] = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'desc')
            ->paginate(15);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.rejected-all', $data);
    }


    public function pendingExcel(Request $request)
    {
        $data['menu'] = 'final-qc';

        $query = DB::table('tbl_factory_jb_laravel as jb')
            ->select([
                'jb.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as jb_operator_name',
                'b.fullname as jb_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_fqc_laravel as fqc', 'fqc.fqc_QC', '=', 'jb.jb_id')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id')
            ->whereNull('fqc.fqc_QC')
            ->where('jb.status', 1);

        // Filters
        if ($request->filled('createdBy')) {
            $query->where('jb.created_by', $request->createdBy);
        }

        if ($request->filled('operator')) {
            $query->where('jb.jb_operator', $request->operator);
        }

        if ($request->filled('checker')) {
            $query->where('jb.jb_incharge', $request->checker);
        }

        if ($request->filled('shift')) {
            $query->where('jb.jb_shift', $request->shift);
        }

        if ($request->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', $request->fromDate);
        }

        if ($request->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', $request->toDate);
        }

        if ($request->filled('batchNo')) {
            $query->where('jb.jb_batchNo', $request->batchNo);
        }

        $AllLists = $query->groupBy('jb.jb_barcode')
            ->orderBy('jb.created_at', 'DESC')
            ->get();

        $fileName = 'pending_FQC_report_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'SL No',
            'Date',
            'Time',
            'Shift',
            'Bar Code',
            'Batch No',
            'Wattage',
            'Cell Size',
            'Operator',
            'Incharge',
            'Created By'
        ];

        $callback = function () use ($AllLists, $columns) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $columns);

            foreach ($AllLists as $key => $row) {

                fputcsv($file, [
                    $key + 1,
                    !empty($row->jb_date)
                        ? \Carbon\Carbon::parse($row->jb_date)->format('d/m/Y')
                        : '',
                    !empty($row->jb_time)
                        ? \Carbon\Carbon::parse($row->jb_time)->format('h:i A')
                        : '',
                    $row->shiftdtl,
                    $row->jb_barcode,
                    $row->jb_batchNo,
                    $row->wattage,
                    $row->cellSize,
                    $row->jb_operator_name,
                    $row->jb_incharge_name,
                    $row->createdBy
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function passedExcel(Request $request)
    {
        $data['menu'] = 'final-qc';

        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', 1);

        // Filters
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->createdBy);
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->operator);
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->checker);
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->shift);
        }

        if ($request->filled('fromDate')) {
            $query->whereDate('fqc.created_at', '>=', $request->fromDate);
        }

        if ($request->filled('toDate')) {
            $query->whereDate('fqc.created_at', '<=', $request->toDate);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->batchNo);
        }

        $AllLists = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'DESC')
            ->get();

        $fileName = 'passed_FQC_report_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'SL No',
            'Date',
            'Time',
            'Shift',
            'Bar Code',
            'Batch No',
            'Wattage',
            'Operator',
            'Incharge',
            'Grade',
            'Created By'
        ];

        $callback = function () use ($AllLists, $columns) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($file, $columns);

            foreach ($AllLists as $key => $row) {

                fputcsv($file, [
                    $key + 1,
                    !empty($row->fqc_date)
                        ? \Carbon\Carbon::parse($row->fqc_date)->format('d/m/Y')
                        : '',
                    !empty($row->fqc_time)
                        ? \Carbon\Carbon::parse($row->fqc_time)->format('h:i A')
                        : '',
                    $row->shiftdtl,
                    $row->fqc_barcode,
                    $row->fqc_batchNo,
                    $row->wattage,
                    $row->fqc_operator_name,
                    $row->fqc_incharge_name,
                    $row->scan_grade,
                    $row->createdBy
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function rejectedExcel(Request $request)
    {
        $data['menu'] = 'final-qc';

        $query = DB::table('tbl_factory_fqc_laravel as fqc')
            ->select([
                'fqc.*',
                'psl.wattage',
                'sh.shift as shiftdtl',
                'a.fullname as fqc_operator_name',
                'b.fullname as fqc_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'fqc.fqc_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'fqc.fqc_batchNo')
            ->leftJoin('mstr_emp as a', 'fqc.fqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'fqc.fqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'fqc.created_by', '=', 'c.id')
            ->where('fqc.status', '<>', 1);

        // Filters
        if ($request->filled('createdBy')) {
            $query->where('fqc.created_by', $request->createdBy);
        }

        if ($request->filled('operator')) {
            $query->where('fqc.fqc_operator', $request->operator);
        }

        if ($request->filled('checker')) {
            $query->where('fqc.fqc_incharge', $request->checker);
        }

        if ($request->filled('shift')) {
            $query->where('fqc.fqc_shift', $request->shift);
        }

        if ($request->filled('fromDate')) {
            $query->whereDate('fqc.created_at', '>=', $request->fromDate);
        }

        if ($request->filled('toDate')) {
            $query->whereDate('fqc.created_at', '<=', $request->toDate);
        }

        if ($request->filled('batchNo')) {
            $query->where('fqc.fqc_batchNo', $request->batchNo);
        }

        $AllLists = $query->groupBy('fqc.fqc_id')
            ->orderBy('fqc.created_at', 'DESC')
            ->get();

        $fileName = 'rejected_FQC_report_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'SL No',
            'Date',
            'Time',
            'Shift',
            'Bar Code',
            'Batch No',
            'Wattage',
            'Operator',
            'Incharge',
            'Created By',
            'Status'
        ];

        $callback = function () use ($AllLists, $columns) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $columns);

            foreach ($AllLists as $key => $row) {

                fputcsv($file, [
                    $key + 1,
                    !empty($row->fqc_date)
                        ? \Carbon\Carbon::parse($row->fqc_date)->format('d/m/Y')
                        : '',
                    !empty($row->fqc_time)
                        ? \Carbon\Carbon::parse($row->fqc_time)->format('h:i A')
                        : '',
                    $row->shiftdtl,
                    $row->fqc_barcode,
                    $row->fqc_batchNo,
                    $row->wattage,
                    $row->fqc_operator_name,
                    $row->fqc_incharge_name,
                    $row->createdBy,
                    $row->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }




    public function add()
    {
        $data['menu'] = 'final-qc';
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
        return view('ProductionLineUp.FinalQC.add', $data);
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
                ->where('bushing_batchNo', $batchNo)
                //->where('bushing_id', $btch_viewNo)
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
        $passExists = DB::table('tbl_factory_jb_laravel')
            ->where('jb_barcode', $barcode)
            ->where('jb_batchNo', $batchNo)
            ->exists();

        // Check if barcode already used in JB
        $elQcExists = DB::table('tbl_factory_fqc_laravel')
            ->where('fqc_barcode', $barcode)
            ->where('fqc_batchNo', $batchNo)
            ->exists();

        // If found in EL QC - INVALID
        if ($elQcExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in Final QC.',
            ]);
        }
        if (!$passExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not passed Junction Box against this batchno.',
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

            //ELQC Auto Entry

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

            //Ninetydegree QC Auto Entry

            //REWORK
            $exists = DB::table('tbl_factory_ninetydeg_laravel')
                ->where('ninetydeg_barcode', request()->input('barCode'))
                ->where('status', '<>', '1')
                ->exists();
            if ($exists == true) {
                $qry = NinetyDeg_Model::where('ninetydeg_barcode', $request->input('barCode'));
                $data = array(
                    'status'        => '1',
                    'rwrk_status'   => '1'
                );

                $res =  $qry->update($data);
            }

            //Normal
            $exists = DB::table('tbl_factory_ninetydeg_laravel')
                ->where('ninetydeg_barcode', $request->input('barCode'))
                ->exists();
            if ($exists == false) {

                $data = array(
                    'ninetydeg_id'          => $demoId,
                    'ninetydeg_date'        => date('d-m-Y'),
                    'ninetydeg_time'        => date('H:i:s'),
                    'ninetydeg_operator'    => $request->input('operator'),
                    'ninetydeg_source'      => 'ELQC',
                    'ninetydeg_laminatorNo' => $demoId,
                    'ninetydeg_batchNo'     => $request->input('batchNo'),
                    'ninetydeg_incharge'    => $request->input('incharge'),
                    'ninetydeg_cycle_no'    => '1',
                    'ninetydeg_shift'       => $request->input('shift'),
                    'ninetydeg_plant'       => $request->input('plant'),
                    'status'                => '1',
                    'rwrk_status'           => '0',
                    'ninetydeg_pDefectRsn'  => 'No Damage',
                    'ninetydeg_rfid'        => $request->input('rfid'),
                    'ninetydeg_barcode'     => $request->input('barCode'),
                    'created_by'            => $request->session()->get('empId')
                );

                $res = NinetyDeg_Model::create($data);

                NinetyDegHist_Model::create([
                    'ninetydeg_id' => $demoId,
                    'action'       => 'Raised',
                    'ip_address'   => $this->getUserIP(),
                    'created_by'   => auth()->id()
                ]);
            }

            //Junctionbox Auto Entry
            $exists = DB::table('tbl_factory_jb_laravel')
                ->where('jb_barcode', $request->input('barCode'))
                ->exists();
            if ($exists == false) {

                $data = array(
                    'jb_id' => $demoId,
                    'jb_date' => date('d-m-Y'),
                    'jb_time' => date('H:i:s'),
                    'jb_operator' => $request->input('operator'),
                    'jb_source' => '90 deg QC',
                    'jb_QC' => $demoId,
                    'jb_batchNo' => $request->input('batchNo'),
                    'jb_incharge' => $request->input('incharge'),
                    'jb_cycle_no' => '1',
                    'jb_shift' => $request->input('shift'),
                    'jb_plant' => $request->input('plant'),
                    'status' => '1',
                    'jb_pDefectRsn' => 'No Damage',
                    'jb_rfid' => $request->input('rfid'),
                    'jb_barcode' => $request->input('barCode'),
                    'created_by' => $request->session()->get('empId')
                );


                $res = JB_Model::create($data);

                JB_Hist_Model::create([
                    'jb_id' => $demoId,
                    'action' => 'Raised',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => $request->session()->get('empId')
                ]);
            }

            $Bexists = DB::table('factory_serial_number_details')
                ->where('sl_no', $request->input('barCode'))
                ->where('status', '<>', 'USED')
                ->exists();

            //$Bexists = true;

            $PreExists = true;

            if ($Bexists == true && $PreExists == true) {

                $exists = DB::table('tbl_factory_fqc_laravel')
                    ->where('fqc_barcode', $request->input('barCode'))
                    ->exists();

                if ($exists == false) {

                    $qcId = DB::table('tbl_factory_jb_laravel')
                        ->where('jb_barcode', $request->input('barCode'))
                        ->where('jb_batchNo', $request->input('batchNo'))
                        ->value('jb_id');

                    $id = date('YmdHis');
                    $data = array(
                        'fqc_id' => $id,
                        'fqc_date' => date('d-m-Y'),
                        'fqc_time' => date('H:i:s'),
                        'fqc_operator' => $request->input('operator'),
                        'fqc_source' => 'Junction Box',
                        'fqc_QC' => $qcId,
                        'fqc_batchNo' => $request->input('batchNo'),
                        'fqc_incharge' => $request->input('incharge'),
                        'fqc_cycle_no' => $request->input('cycleNo'),
                        'fqc_shift' => $request->input('shift'),
                        'fqc_plant' => $request->input('plant'),
                        'status' => $request->input('el_type'),
                        'fqc_pDefectRsn' => $request->input('p_reject_reason'),
                        'fqc_barcode' => $request->input('barCode'),
                        'scan_grade' => $request->input('el_grade'),
                        'scan_flag' => 1,
                        'created_by' => $request->session()->get('empId')
                    );


                    $res = FinalQC_Model::create($data);

                    FinalQC_Hist_Model::create([
                        'fqc_id' => $id,
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
                                'fqc_id' => $id,
                                'cell_no' => $cell_no,
                                'cell_qty' => $cell_qtys[$i] ?? null,
                                'defectRsn' => $dmgMat_reasons[$i] ?? null,
                                'defectCatgry' => $defect_categories[$i] ?? null,
                                'res_prsn' => $res_prsns[$i] ?? null,
                                'res_machine' => $res_machines[$i] ?? null,
                                'status' => '0'
                            );

                            FinalQC_Defect_Model::create($defectData);
                        }
                    }


                    //L I N K   W I T H   P R O D U C TI O N   S T O C K



                    $batchRawData = DB::table('tbl_factory_production_setup_laravel')
                        ->where('batchNo', $request->input('batchNo'))
                        ->first();



                    $data = array(
                        'userID'             => $request->session()->get('empId'),
                        'status'             => 0,
                        'Forward_Status'     => 0,
                        'Approve_status'     => 'APPROVE',
                        'Approve_Step'       => 1,
                        'Unit_Name'          => 130,
                        'Plant_Name'         => 743,
                        'Organization_Name'  => 4,
                        'BU_Name'            => 3,
                        'Shift'              => $batchRawData->fromShift,
                        'Production_Date'    => date('Y-m-d'),
                        'Raw_Material'       => $batchRawData->finishGood,
                        'remarks'            => "Direct Stock Entry From FQC",
                        'UOM'                => 'Nos',
                        'Rate'               => '0',
                        'Quantity'           => 1,
                        'Total_amount'       => '0'
                    );


                    $prodEntry = Production::create($data);

                    $prodEntryID = $prodEntry->id;

                    $prev_sl_no = productionBatch::max('sl');
                    $slNo = $prev_sl_no + 1;
                    $slAlfa = 'SLNO' . $slNo;

                    $data = array(
                        'productionID'      => $prodEntryID,
                        'batch_no'          => 0,
                        'sl_no'             => 0,
                    );

                    DB::table('production_batch')->insert([
                        'productionID' => $prodEntryID,
                        'batch_no'     => $request->input('batchNo'),
                        'sl_no'        => $slAlfa,
                        'serail_check' => $request->input('barCode'),
                        'batch'        => $request->input('batchNo'),
                        'sl'           => $slNo,
                        'created_at'   => now(), // Laravel helper for current timestamp
                        'updated_at'   => now(),
                    ]);

                    // Check serial number exists in factory_serial_number_details
                    $serialExists = DB::table('factory_serial_number_details')
                        ->where('sl_no', $request->input('barCode'))
                        ->exists();

                    if ($serialExists) {

                        DB::table('factory_serial_number_details')
                            ->where('sl_no', $request->input('barCode'))
                            ->update([
                                'status' => 'USED',
                                'updated_at' => now()
                            ]);
                    }


                    //$prodEntry = ProductionBatch::create($data);



                    //L I N K   W I T H   P R O D U C TI O N   S T O C K




                    $lock = request()->input('lock');
                    $batchNo = request()->input('batchNo');
                    $oprtr = request()->input('operator');
                    $incherge = request()->input('incharge');
                    $shift = request()->input('shift');
                    $plant = request()->input('plant');
                    $page = request()->input('page');
                    if ($res->exists) {
                        if ($lock && $page) {
                            $url = 'production-lineup/final-qc/add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                            return redirect($url)->with('success', ' Final QC data stored successfully!');
                        } else {
                            //$url = ;
                            return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored successfully!');
                        }
                    }
                } else {
                    //$url = ;
                    return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored failed! Duplicate Barcode');
                }
            } else {
                //$url = ;
                return redirect('production-lineup/final-qc')->with('success', ' Final QC data stored failed! This Barcode already Used');
            }
        }
    }


    public function view_fqc($id = null)
    {

        //echo 'hi'; exit;
        $data['menu'] = 'junctionbox';
        $data['laminatorDetails'] = DB::table('tbl_factory_fqc_laravel as fqc')
            ->leftJoin('mstr_emp as emp1', 'fqc.fqc_operator', '=', 'emp1.id')
            ->leftJoin('mstr_emp as emp2', 'fqc.fqc_incharge', '=', 'emp2.id')
            ->leftJoin('hr_mstr_shift as sh', 'fqc.fqc_shift', '=', 'sh.id')
            ->select('fqc.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
            ->where('fqc.fqc_id', $id)
            ->first();
        $data['defectDetails'] = DB::table('tbl_factory_fqc_defect_laravel as def')
            ->select('def.*')
            ->where('def.fqc_id', $id)
            ->get();
        $data['laminatorHistory'] = DB::table('tbl_factory_fqc_history_laravel as history')
            ->select('history.*', 'emp.fullname as created_by')
            ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
            ->where('history.fqc_id', $id)
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
            ->where('mstr_emp.status', '1')
            ->get();
        $batchNo = $data['laminatorDetails']->fqc_batchNo;
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.FinalQC.view_fqc', $data);
    }
}
