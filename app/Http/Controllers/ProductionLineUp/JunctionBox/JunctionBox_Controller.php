<?php

namespace App\Http\Controllers\ProductionLineUp\JunctionBox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionLineUp\{JB_Model, JB_Damage_Model, JB_Hist_Model};
use App\Models\ProductionLineUp\{NinetyDeg_Model, NinetyDeg_Model_RWRK, NinetyDegDamage_Model, NinetyDegDamage_Model_RWRK, NinetyDegHist_Model};
use App\Models\ProductionLineUp\{EL_QC, EL_QC_Defect, EL_QC_RWRK, EL_QC_Defect_RWRK, EL_QC_History};
use App\Models\ProductionLineUp\{Bushing_Model, BushingMaterial_Model, BushingDamageMaterial_Model};
use App\Models\ProductionLineUp\{ProductSetUpMaterial_Model};
use Carbon\Carbon;

class JunctionBox_Controller extends Controller
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
        $data['menu'] = 'junctionbox';
        
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

        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator_name',
                'b.fullname as ninetydeg_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_jb_laravel as jb', 'jb.jb_barcode', '=', 'ninetydeg.ninetydeg_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id')
            // Base WHERE conditions
            ->whereNull('jb.jb_QC')
            ->where('ninetydeg.status', 1);
        
        // 2. Conditionally apply filters safely using request() or $request->input()
        if (request()->filled('createdBy')) {
            $query->where('ninetydeg.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', request('batchNo'));
        }
        
        
        $data['AllLists'] = $query->groupBy('ninetydeg.ninetydeg_barcode')
            ->orderByDesc('ninetydeg.created_at')
            ->paginate(10);
            
            $data['AllLaminatorLists'] = [];

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.index', $data);
    }
    
    public function passed()
    {
        $data['menu'] = 'junctionbox';
        
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

        $Condition = '';
        $Cond = [];

        $query = DB::table('tbl_factory_jb_laravel as jb')
        ->select([
            'jb.*',
            'psl.wattage',
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '=',1);

        $data['AllLaminatorLists'] = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.passed', $data);
    }
    
    public function rejected()
    {
        $data['menu'] = 'junctionbox';
        
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
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '<>',1);

        $data['AllLaminatorLists'] = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.rejected', $data);
    }



    public function indexAll()
    {
        $data['menu'] = 'junctionbox';
        
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

        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator_name',
                'b.fullname as ninetydeg_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_jb_laravel as jb', 'jb.jb_barcode', '=', 'ninetydeg.ninetydeg_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id')
            // Base WHERE conditions
            ->whereNull('jb.jb_QC')
            ->where('ninetydeg.status', 1);
        
        // 2. Conditionally apply filters safely using request() or $request->input()
        if (request()->filled('createdBy')) {
            $query->where('ninetydeg.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', request('batchNo'));
        }
        
        
        $data['AllLists'] = $query->groupBy('ninetydeg.ninetydeg_barcode')
            ->orderByDesc('ninetydeg.created_at')
            ->paginate(10);
            
            $data['AllLaminatorLists'] = [];

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.index', $data);
    }
    
    public function passedAll()
    {
        $data['menu'] = 'junctionbox';
        
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

        $Condition = '';
        $Cond = [];

        $query = DB::table('tbl_factory_jb_laravel as jb')
        ->select([
            'jb.*',
            'psl.wattage',
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '=',1);

        $data['AllLaminatorLists'] = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.passed', $data);
    }
    
    public function rejectedAll()
    {
        $data['menu'] = 'junctionbox';
        
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
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '<>',1);

        $data['AllLaminatorLists'] = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
            ->paginate(10);

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.rejected', $data);
    }
    
    
    
    public function pendingExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        $query = DB::table('tbl_factory_ninetydeg_laravel as ninetydeg')
            ->select([
                'ninetydeg.*',
                'psl.wattage',
                'psml.size as cellSize',
                'sh.shift as shiftdtl',
                'a.fullname as ninetydeg_operator_name',
                'b.fullname as ninetydeg_incharge_name',
                'c.fullname as createdBy'
            ])
            ->leftJoin('tbl_factory_jb_laravel as jb', 'jb.jb_barcode', '=', 'ninetydeg.ninetydeg_barcode')
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'ninetydeg.ninetydeg_shift')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'ninetydeg.ninetydeg_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'ninetydeg.ninetydeg_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'ninetydeg.ninetydeg_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'ninetydeg.created_by', '=', 'c.id')
            // Base WHERE conditions
            ->whereNull('jb.jb_QC')
            ->where('ninetydeg.status', 1);
        
        // 2. Conditionally apply filters safely using request() or $request->input()
        if (request()->filled('createdBy')) {
            $query->where('ninetydeg.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('ninetydeg.ninetydeg_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('ninetydeg.ninetydeg_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('ninetydeg.ninetydeg_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('ninetydeg.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('ninetydeg.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('ninetydeg.ninetydeg_batchNo', request('batchNo'));
        }
        
        
        $AllLists = $query->groupBy('ninetydeg.ninetydeg_barcode')
            ->orderByDesc('ninetydeg.created_at')
            ->get();


        $fileName = 'pending_ELQC_report_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt',  'Bus Bar', 'Operator', 'Incharge'];//'Cell Efficiency',

        // 4. Create a callback to stream the data
        $callback = function() use($AllLists, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows
            foreach ($AllLists as $key=>$row) {
              $sl = $key+1;
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->ninetydeg_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->ninetydeg_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->ninetydeg_barcode,
                    $row->ninetydeg_source ?? 'Layout',
                    $row->wattage,
                    //$row->cellSize,
                    $row->bus_bar ?? '-',
                    $row->ninetydeg_operator_name,
                    $row->ninetydeg_incharge_name,
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
        $query = DB::table('tbl_factory_jb_laravel as jb')
        ->select([
            'jb.*',
            'psl.wattage',
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '=',1);

        $AllLists = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
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
        $callback = function() use($AllLists, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows
            
            foreach ($AllLists as $key=>$row) {
              $sl = $key+1;
                //if ($row->status == '1' && $row->rwrk_status == '1'){
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->jb_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->jb_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->jb_barcode,
                    $row->jb_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize ?? '-',
                    $row->bus_bar ?? '-',
                    $row->jb_operator_name,
                    $row->jb_incharge_name,
                ]);
              //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }
    // public function passedExcel(Request $request)
    // {
    //     //$data['menu'] = 'elqc-setup';
    
    //     $query = DB::table('tbl_factory_jb_laravel as jb')
    //     ->select([
    //         'jb.*',
    //         'psl.wattage',
    //         'sh.shift as shiftdtl',
    //         'a.fullname as jb_operator_name',
    //         'b.fullname as jb_incharge_name',
    //         'c.fullname as createdBy'
    //     ])
    //     ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
    //     ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
    //     ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
    //     ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
    //     ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

    //     // 2. Safely apply conditional filters using request()->filled()
    //     if (request()->filled('createdBy')) {
    //         $query->where('jb.created_by', request('createdBy'));
    //     }
    //     if (request()->filled('operator')) {
    //         $query->where('jb.jb_operator', request('operator'));
    //     }
    //     if (request()->filled('checker')) {
    //         $query->where('jb.jb_incharge', request('checker'));
    //     }
    //     if (request()->filled('shift')) {
    //         $query->where('jb.jb_shift', request('shift'));
    //     }
    //     if (request()->filled('fromDate')) {
    //         $query->whereDate('jb.created_at', '>=', request('fromDate'));
    //     }
    //     if (request()->filled('toDate')) {
    //         $query->whereDate('jb.created_at', '<=', request('toDate'));
    //     }
    //     if (request()->filled('batchNo')) {
    //         $query->where('jb.jb_batchNo', request('batchNo'));
    //     }
    //     $query->where('jb.status', '=',1);

        
    //     $AllLists = $query->groupBy('jb.jb_id')
    //         ->orderByDesc('jb.created_at')->get();
            
    //     print_r($AllLists); exit;
    
    //     $fileName = 'passed_ELQC_report_' . date('Ymd_His') . '.csv';
    //     $headers = [
    //         "Content-type"        => "text/csv",
    //         "Content-Disposition" => "attachment; filename=$fileName",
    //         "Pragma"              => "no-cache",
    //         "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
    //         "Expires"             => "0"
    //     ];
    
    //     $columns = ['SL No', 'Date', 'Time', 'Shift', 'Bar Code', 'Source', 'Watt', 'Cell Efficiency', 'Bus Bar', 'Operator', 'Incharge'];
    
    //     // Create a callback to stream the data
    //     $callback = function() use($AllLists, $columns) {
    //         $file = fopen('php://output', 'w');
            
    //         // Add UTF-8 BOM for Excel to recognize special characters correctly
    //         fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    
    //         // Write column headers
    //         fputcsv($file, $columns);
    
    //         // Write data rows
    //         foreach ($AllLists as $key => $row) {
    //             $sl = $key + 1;
    //             fputcsv($file, [
    //                 $sl,
    //                 \Carbon\Carbon::parse($row->jb_date)->format('d/m/Y'),
    //                 \Carbon\Carbon::parse($row->jb_time)->format('h:i A'),
    //                 $row->shiftdtl,
    //                 $row->jb_barcode,
    //                 $row->jb_source ?? 'Layout',
    //                 $row->wattage,
    //                 $row->cellSize ?? '-',
    //                 $row->bus_bar ?? '-',
    //                 $row->jb_operator_name,
    //                 $row->jb_incharge_name,
    //             ]);
    //         }
    
    //         fclose($file);
    //     };
    
    //     // Return the response as a stream
    //     return response()->stream($callback, 200, $headers);
    // }
    
    public function rejectedExcel(Request $request)
    {
        // Initialize the query builder
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
        ->selectRaw('SUM(cell_qty)')
        ->whereColumn('d2.elqcId', 'elqc.elqc_id');

      
        $query = DB::table('tbl_factory_jb_laravel as jb')
        ->select([
            'jb.*',
            'psl.wattage',
            'sh.shift as shiftdtl',
            'a.fullname as jb_operator_name',
            'b.fullname as jb_incharge_name',
            'c.fullname as createdBy'
        ])
        ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'jb.jb_shift')
        ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'jb.jb_batchNo')
        ->leftJoin('mstr_emp as a', 'jb.jb_operator', '=', 'a.id')
        ->leftJoin('mstr_emp as b', 'jb.jb_incharge', '=', 'b.id')
        ->leftJoin('mstr_emp as c', 'jb.created_by', '=', 'c.id');

        // 2. Safely apply conditional filters using request()->filled()
        if (request()->filled('createdBy')) {
            $query->where('jb.created_by', request('createdBy'));
        }
        if (request()->filled('operator')) {
            $query->where('jb.jb_operator', request('operator'));
        }
        if (request()->filled('checker')) {
            $query->where('jb.jb_incharge', request('checker'));
        }
        if (request()->filled('shift')) {
            $query->where('jb.jb_shift', request('shift'));
        }
        if (request()->filled('fromDate')) {
            $query->whereDate('jb.created_at', '>=', request('fromDate'));
        }
        if (request()->filled('toDate')) {
            $query->whereDate('jb.created_at', '<=', request('toDate'));
        }
        if (request()->filled('batchNo')) {
            $query->where('jb.jb_batchNo', request('batchNo'));
        }
        $query->where('jb.status', '<>',1);

        $AllLists = $query->groupBy('jb.jb_id')
            ->orderByDesc('jb.created_at')
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
        $callback = function() use($AllLists, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to recognize special characters correctly
            fputs($file, (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Write column headers
            fputcsv($file, $columns);

            // Write data rows
            foreach ($AllLists as $key=>$row) {
              $sl = $key+1;
              //if ($row->status == '2' && $row->rwrk_status == '2'){
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->jb_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->jb_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->jb_barcode,
                    $row->jb_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize ?? '-',
                    $row->bus_bar ?? '-',
                    $row->jb_operator_name,
                    $row->jb_incharge_name,
                ]);
              //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }
    
    
    
    public function addJunctionBox()
    {
        $data['menu'] = 'junctionbox';
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

        // $batchNo = request()->get('id');
        // $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
        //     ->select('psl.cellRow', 'psl.celColumn')
        //     ->where('psl.batchNo', $batchNo)
        //     ->first();


        if(isset($_GET['id'])){
          $getBatchNo = $_GET['id'];
          $getBarcode = request()->get('bid');

          $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->join('tbl_factory_material_master_laravel as m', 'm.id', '=', 'psml.material')
            ->select('m.title as matname', 'm.id as matid', 'psl.wattage', 'psml.size AS msize', 'psml.brand AS mbrand')
            ->where('psl.batchNo', $getBatchNo)
            ->get();

          $data['bushingLogo'] = DB::table('tbl_factory_bushing_laravel')
            ->select('bushing_logo')
            ->where('bushing_barCode', $getBarcode)
            ->first();

          
        }

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.add', $data);
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
            //->where('bushing_id', $btch_viewNo)
            ->first();
        } else {
            $exists = DB::table('tbl_factory_bushing_laravel')
            ->select('bushing_id' , 'bushing_logo')
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
        $passExists = DB::table('tbl_factory_ninetydeg_laravel')
            ->where('ninetydeg_barcode', $barcode)
            ->where('ninetydeg_batchNo', $batchNo)
            ->exists(); 
            
        // Check if barcode already used in JB
        $elQcExists = DB::table('tbl_factory_jb_laravel')
            ->where('jb_barcode', $barcode)
            ->where('jb_batchNo', $batchNo)
            ->exists();
    
        // If found in EL QC - INVALID
        if ($elQcExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in Junction Box.',
            ]);
        }
        if (!$passExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode is not passed 90 degree QC against this batchno.',
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


    public function insert(Request $request){


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

                $materials = ProductSetUpMaterial_Model::where('batchNo',request()->input('batchNo'))->get();

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
                $qry = EL_QC::where('elqc_barcode',$request->input('barCode'));
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
                $qry = NinetyDeg_Model::where('ninetydeg_barcode',$request->input('barCode'));
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

            
            $Bexists = true;
            $PreExists = true; 
            
            if($Bexists == true && $PreExists == true){
            
                $exists = DB::table('tbl_factory_jb_laravel')
                  ->where('jb_barcode', $request->input('barCode'))
                  ->exists();
                  
                if($exists == false){
                    
                    $qcId = DB::table('tbl_factory_ninetydeg_laravel')
                  ->where('ninetydeg_barcode', $request->input('barCode'))
                  ->where('ninetydeg_batchNo', $request->input('batchNo'))
                  ->value('ninetydeg_id');
        
                    $id = date('YmdHis');
                    $data = array(
                        'jb_id' => $id,
                        'jb_date' => date('d-m-Y'),
                        'jb_time' => date('H:i:s'),
                        'jb_operator' => $request->input('operator'),
                        'jb_source' => '90 deg QC',
                        'jb_QC' => $qcId,
                        'jb_batchNo' => $request->input('batchNo'),
                        'jb_incharge' => $request->input('incharge'),
                        'jb_cycle_no' => $request->input('cycleNo'),
                        'jb_shift' => $request->input('shift'),
                        'jb_plant' => $request->input('plant'),
                        'status' => $request->input('el_type'),
                        'jb_pDefectRsn' => $request->input('p_reject_reason'),
                        'jb_rfid' => $request->input('rfid'),
                        'jb_barcode' => $request->input('barCode'),
                        'scan_flag' => 1,
                        'created_by' => $request->session()->get('empId')
                    );
        
        
                    $res = JB_Model::create($data);
                    
                    JB_Hist_Model::create([
                        'jb_id' => $id,
                        'action' => 'Raised',
                        'ip_address' => $this->getUserIP(),
                        'created_by' => auth()->id()
                    ]);
                    $type = $request->input('type', []);
                    $size = $request->input('size', []);
                    $brand = $request->input('brand', []);
                    $qty = $request->input('qty', []);
                    $uom = $request->input('uom', []);
        
                    if ($request->input('el_type') === '0' && is_array($type) && count($type) > 0) {
                        foreach ($type as $i => $dfctType) {
                            // skip empty rows (optional)
                            if ($dfctType === null || $dfctType === '') {
                                continue;
                            }
        
                            $defectData = array(
                                'jb_Id' => $id,
                                'type' => $dfctType,
                                'size' => $size[$i] ?? null,
                                'brand' => $brand[$i] ?? null,
                                'qty' => $qty[$i] ?? null,
                                'uom' => $uom[$i] ?? null,
                            );
        
                            JB_Damage_Model::create($defectData);
                        }
                    }
                    $lock = request()->input('lock');
                    $batchNo = request()->input('batchNo');
                    $oprtr = request()->input('operator');
                    $incherge = request()->input('incharge');
                    $shift = request()->input('shift');
                    $plant = request()->input('plant');
                    $page = request()->input('page');
                    if ($res->exists) {
                        if ($lock && $page) {
                            $url = 'production-lineup/junctionbox/add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                            return redirect($url)->with('success', ' Junction Box data stored successfully!');
                        } else {
                           //$url = ;
                            return redirect('production-lineup/junctionbox')->with('success', ' Junction Box data stored successfully!');
                        }
                    }
                }else {
                   //$url = ;
                  return redirect('production-lineup/junctionbox')->with('success', ' Junction Box data stored faild! Duplicate Barcode');
                }
            }else {
                   //$url = ;
              return redirect('production-lineup/junctionbox')->with('success', ' Junction Box data stored faild! Barcode Not Passed in Either Layout Setup or Ninety Degree QC');
            }
        }
    }


    public function view_jb($id = null){
      
        //echo 'hi'; exit;
      $data['menu'] = 'junctionbox';
      $data['laminatorDetails'] = DB::table('tbl_factory_jb_laravel as jb')
          ->leftJoin('mstr_emp as emp1', 'jb.jb_operator', '=', 'emp1.id')
          ->leftJoin('mstr_emp as emp2', 'jb.jb_incharge', '=', 'emp2.id')
          ->leftJoin('hr_mstr_shift as sh', 'jb.jb_shift', '=', 'sh.id')
          ->select('jb.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
          ->where('jb.jb_id', $id)
          ->first();
      $data['defectDetails'] = DB::table('tbl_factory_jb_defect_laravel as def')
          ->select('def.*')
          ->where('def.jb_Id', $id)
          ->get();
      $data['laminatorHistory'] = DB::table('tbl_factory_jb_history_laravel as history')
          ->select('history.*', 'emp.fullname as created_by')
          ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
          ->where('history.jb_id', $id)
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
      $batchNo = $data['laminatorDetails']->jb_batchNo;
      $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
          ->select('psl.cellRow', 'psl.celColumn')
          ->where('psl.batchNo', $batchNo)
          ->first();
          
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.junctionbox.view_jb', $data);
    
    }


}
