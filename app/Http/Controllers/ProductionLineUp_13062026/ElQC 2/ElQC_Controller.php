<?php

namespace App\Http\Controllers\ProductionLineUp\ElQC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductionLineUp\{EL_QC, EL_QC_Defect, EL_QC_History};

class ElQC_Controller extends Controller
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


    public function index(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $query = DB::table('tbl_factory_bushing_laravel as bol')
          ->select([
              'bol.*',
              'elqc.elqc_id', 'elqc.rwrk_status', 'elqc.status', 'elqc.elqc_source',
              'psl.wattage',
              'psml.size as cellSize',
              'sh.shift as shiftdtl',
              'a.fullname as bushing_operator_name',
              'b.fullname as bushing_incherge_name',
              'c.fullname as createdBy'
          ])
          ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

        // Apply Initial Base Conditions
        $query->where(function ($q) {
            $q->whereNull('elqc.elqc_bushingNo')
              ->orWhere(function ($sub) {
                  $sub->where('elqc.rwrk_status', '')
                      ->where('elqc.status', '0');
              });
        })->where('bol.bushing_hasDamage', 'No');

        // Apply Dynamic Filters from Request
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

        // Group, Order, and Paginate
        $data['AllLists'] = $query->groupBy('bol.bushing_id')
            ->orderBy('bol.created_at', 'DESC')
            ->paginate(15); 

        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.index', $data);
    }   
    public function elqcPassed(Request $request)
    {
        $data['menu'] = 'elqc-setup';
    
        // 1. Create a joined subquery for cell damage to avoid row-by-row selection
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel')
            ->select('elqcId', DB::raw('SUM(cell_qty) as total_damage'))
            ->groupBy('elqcId');
    
        // 2. Build the main query
        $query = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select([
                'elqc.*',
                'psl.wattage',
                'psml.size as cellSize',
                'bol.bushing_id',
                'sh.shift as shiftdtl',
                'a.fullname as elqc_operator_name',
                'b.fullname as elqc_incharge_name',
                'c.fullname as createdByName',
                'dmg.total_damage as no_of_cell_damage' // From the join
            ])
            // Join the pre-aggregated damage counts
            ->leftJoinSub($damageSubquery, 'dmg', function ($join) {
                $join->on('elqc.elqc_id', '=', 'dmg.elqcId');
            })
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
            ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');
    
        // 3. Optimized Logical Filters
        // $query->where(function ($q) {
        //     $q->whereNull('elqc.elqc_bushingNo')
        //       ->orWhere(function ($sub) {
        //           $sub->where('elqc.rwrk_status', '')
        //               ->where('elqc.status', '0');
        //       });
        // })->where('bol.bushing_hasDamage', 'No');
    
        // 4. Dynamic Filters
        $filters = [
            'created_by' => 'createdBy',
            'elqc_operator' => 'operator',
            'elqc_incharge' => 'checker',
            'elqc_shift' => 'shift',
            'elqc_batchNo' => 'batchNo'
        ];
    
        foreach ($filters as $column => $input) {
            if ($request->filled($input)) {
                $query->where("elqc.$column", $request->input($input));
            }
        }
    
        if ($request->filled('fromDate')) {
            $query->whereDate('elqc.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('elqc.created_at', '<=', $request->toDate);
        }
        $query->where('elqc.status', '=', '1');
        
        //dd($query);
        // 5. Final Execution
        $data['AllELQCLists'] = $query->orderBy('elqc.created_at', 'DESC')
            ->paginate(15);
            
        $data['PermittedMenuList'] = self::PermittedMenuList(session('empId'));
        return view('ProductionLineUp.ElQC.elqc-passed', $data);
    }
    public function elqcRejected(Request $request)
    {
        $data['menu'] = 'elqc-setup';
    
        // 1. Create a joined subquery for cell damage to avoid row-by-row selection
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel')
            ->select('elqcId', DB::raw('SUM(cell_qty) as total_damage'))
            ->groupBy('elqcId');
    
        // 2. Build the main query
        $query = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->select([
                'elqc.*',
                'psl.wattage',
                'psml.size as cellSize',
                'bol.bushing_id',
                'sh.shift as shiftdtl',
                'a.fullname as elqc_operator_name',
                'b.fullname as elqc_incharge_name',
                'c.fullname as createdByName',
                'dmg.total_damage as no_of_cell_damage' // From the join
            ])
            // Join the pre-aggregated damage counts
            ->leftJoinSub($damageSubquery, 'dmg', function ($join) {
                $join->on('elqc.elqc_id', '=', 'dmg.elqcId');
            })
            ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
            ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
            ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
            ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
            ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
            ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
            ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');
    
        // 3. Optimized Logical Filters
        // $query->where(function ($q) {
        //     $q->whereNull('elqc.elqc_bushingNo')
        //       ->orWhere(function ($sub) {
        //           $sub->where('elqc.rwrk_status', '')
        //               ->where('elqc.status', '0');
        //       });
        // })->where('bol.bushing_hasDamage', 'No');
    
        // 4. Dynamic Filters
        $filters = [
            'created_by' => 'createdBy',
            'elqc_operator' => 'operator',
            'elqc_incharge' => 'checker',
            'elqc_shift' => 'shift',
            'elqc_batchNo' => 'batchNo'
        ];
    
        foreach ($filters as $column => $input) {
            if ($request->filled($input)) {
                $query->where("elqc.$column", $request->input($input));
            }
        }
    
        if ($request->filled('fromDate')) {
            $query->whereDate('elqc.created_at', '>=', $request->fromDate);
        }
        if ($request->filled('toDate')) {
            $query->whereDate('elqc.created_at', '<=', $request->toDate);
        }
        $query->where('elqc.status', '=', 2);
    
        // 5. Final Execution
        $data['AllELQCLists'] = $query->orderBy('elqc.created_at', 'DESC')
            ->paginate(15);
            
        $data['PermittedMenuList'] = self::PermittedMenuList(session('empId'));
        return view('ProductionLineUp.ElQC.elqc-rejected', $data);
    }


    public function pendingExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $query = DB::table('tbl_factory_bushing_laravel as bol')
          ->select([
              'bol.*',
              'elqc.elqc_id', 'elqc.rwrk_status', 'elqc.status', 'elqc.elqc_source',
              'psl.wattage',
              'psml.size as cellSize',
              'sh.shift as shiftdtl',
              'a.fullname as bushing_operator_name',
              'b.fullname as bushing_incherge_name',
              'c.fullname as createdBy'
          ])
          ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

        // Apply Initial Base Conditions
        $query->where(function ($q) {
            $q->whereNull('elqc.elqc_bushingNo')
              ->orWhere(function ($sub) {
                  $sub->where('elqc.rwrk_status', '')
                      ->where('elqc.status', '0');
              });
        })->where('bol.bushing_hasDamage', 'No');

        // Apply Dynamic Filters from Request
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

        // Group, Order, and Paginate
        $AllLists = $query->groupBy('bol.bushing_id')
            ->orderBy('bol.created_at', 'DESC')
            ->get();


        $fileName = 'pending_ELQC_report_' . date('Ymd_His') . '.csv';
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
                fputcsv($file, [
                    $sl,
                    \Carbon\Carbon::parse($row->bushing_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->bushing_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->bushing_barCode,
                    $row->elqc_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize,
                    $row->bus_bar ?? '-',
                    $row->bushing_operator_name,
                    $row->bushing_incherge_name,
                ]);
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }
    public function pendingPDF(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $query = DB::table('tbl_factory_bushing_laravel as bol')
          ->select([
              'bol.*',
              'elqc.elqc_id', 'elqc.rwrk_status', 'elqc.status', 'elqc.elqc_source',
              'psl.wattage',
              'psml.size as cellSize',
              'sh.shift as shiftdtl',
              'a.fullname as bushing_operator_name',
              'b.fullname as bushing_incherge_name',
              'c.fullname as createdBy'
          ])
          ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'bol.bushing_shift')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'bol.bushing_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'bol.bushing_incherge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'bol.created_by', '=', 'c.id');

        // Apply Initial Base Conditions
        $query->where(function ($q) {
            $q->whereNull('elqc.elqc_bushingNo')
              ->orWhere(function ($sub) {
                  $sub->where('elqc.rwrk_status', '')
                      ->where('elqc.status', '0');
              });
        })->where('bol.bushing_hasDamage', 'No');

        // Apply Dynamic Filters from Request
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

        // Group, Order, and Paginate
        $lists = $query->groupBy('bol.bushing_id')
            ->orderBy('bol.created_at', 'DESC')
            ->get(); 

        $data = [
            'title' => 'Pending ELQC Report',
            'date' => date('m/d/Y'),
            'lists' => $lists
        ];

        // If you decide to use DomPDF (standard):
        $pdf = Pdf::loadView('ProductionLineUp.ElQC.pending_pdf', $data)->setPaper('a3', 'landscape');
        return $pdf->download('pending_report.pdf');
            
    }


    public function passedExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
      $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
      ->selectRaw('SUM(cell_qty)')
      ->whereColumn('d2.elqcId', 'elqc.elqc_id');

      // 2. Build the main query
      $query = DB::table('tbl_factory_el_qc_laravel as elqc')
          ->select([
              'elqc.*',
              'psl.wattage',
              'psml.size as cellSize',
              'bol.bushing_id',
              'sh.shift as shiftdtl',
              'a.fullname as elqc_operator_name', // Avoid alias collision with original column
              'b.fullname as elqc_incharge_name',
              'c.fullname as createdByName',
          ])
          ->selectSub($damageSubquery, 'no_of_cell_damage') // Injects the subquery
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
          ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

      // 3. Apply Base Conditions (Initial logical group)
    //   $query->where(function ($q) {
    //       $q->whereNull('elqc.elqc_bushingNo')
    //         ->orWhere(function ($sub) {
    //             $sub->where('elqc.rwrk_status', '')
    //                 ->where('elqc.status', '0');
    //         });
    //   })->where('bol.bushing_hasDamage', 'No');

      // 4. Apply Dynamic Filters (Using $request instead of $_GET)
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
      $query->where('elqc.status', '=', 1);

      // 5. Final Execution with Pagination
      $AllLists = $query->groupBy('elqc.elqc_id')
          ->orderBy('elqc.created_at', 'DESC')
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
                    \Carbon\Carbon::parse($row->elqc_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->elqc_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->elqc_barcode,
                    $row->elqc_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize,
                    $row->bus_bar ?? '-',
                    $row->elqc_operator_name,
                    $row->elqc_incherge_name,
                ]);
              //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }
    public function passedPDF(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
      ->selectRaw('SUM(cell_qty)')
      ->whereColumn('d2.elqcId', 'elqc.elqc_id');

      // 2. Build the main query
      $query = DB::table('tbl_factory_el_qc_laravel as elqc')
          ->select([
              'elqc.*',
              'psl.wattage',
              'psml.size as cellSize',
              'bol.bushing_id',
              'sh.shift as shiftdtl',
              'a.fullname as elqc_operator_name', // Avoid alias collision with original column
              'b.fullname as elqc_incharge_name',
              'c.fullname as createdByName',
          ])
          ->selectSub($damageSubquery, 'no_of_cell_damage') // Injects the subquery
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
          ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

      // 3. Apply Base Conditions (Initial logical group)
    //   $query->where(function ($q) {
    //       $q->whereNull('elqc.elqc_bushingNo')
    //         ->orWhere(function ($sub) {
    //             $sub->where('elqc.rwrk_status', '')
    //                 ->where('elqc.status', '0');
    //         });
    //   })->where('bol.bushing_hasDamage', 'No');

      // 4. Apply Dynamic Filters (Using $request instead of $_GET)
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
      $query->where('elqc.status', '=', 1);

      // 5. Final Execution with Pagination
      $lists = $query->groupBy('elqc.elqc_id')
      ->orderBy('elqc.created_at', 'DESC')
        ->get();


        $data = [
            'title' => 'Passed ELQC Report',
            'date' => date('m/d/Y'),
            'lists' => $lists
        ];

        // If you decide to use DomPDF (standard):
        $pdf = Pdf::loadView('ProductionLineUp.ElQC.passed_pdf', $data)->setPaper('a3', 'landscape');
        return $pdf->download('passed_report.pdf');
            
    }


    public function rejectedExcel(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
      ->selectRaw('SUM(cell_qty)')
      ->whereColumn('d2.elqcId', 'elqc.elqc_id');

      // 2. Build the main query
      $query = DB::table('tbl_factory_el_qc_laravel as elqc')
          ->select([
              'elqc.*',
              'psl.wattage',
              'psml.size as cellSize',
              'bol.bushing_id',
              'sh.shift as shiftdtl',
              'a.fullname as elqc_operator_name', // Avoid alias collision with original column
              'b.fullname as elqc_incharge_name',
              'c.fullname as createdByName',
          ])
          ->selectSub($damageSubquery, 'no_of_cell_damage') // Injects the subquery
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
          ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

      // 3. Apply Base Conditions (Initial logical group)
    //   $query->where(function ($q) {
    //       $q->whereNull('elqc.elqc_bushingNo')
    //         ->orWhere(function ($sub) {
    //             $sub->where('elqc.rwrk_status', '')
    //                 ->where('elqc.status', '0');
    //         });
    //   })->where('bol.bushing_hasDamage', 'No');

      // 4. Apply Dynamic Filters (Using $request instead of $_GET)
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
      $query->where('elqc.status', '=', 2);

      // 5. Final Execution with Pagination
      $AllLists = $query->groupBy('elqc.elqc_id')
          ->orderBy('elqc.created_at', 'DESC')
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
                    \Carbon\Carbon::parse($row->elqc_date)->format('d/m/Y'),
                    \Carbon\Carbon::parse($row->elqc_time)->format('h:i A'),
                    $row->shiftdtl,
                    $row->elqc_barcode,
                    $row->elqc_source ?? 'Layout',
                    $row->wattage,
                    $row->cellSize,
                    $row->bus_bar ?? '-',
                    $row->elqc_operator_name,
                    $row->elqc_incherge_name,
                ]);
              //}
            }

            fclose($file);
        };

        // 5. Return the response as a stream
        return response()->stream($callback, 200, $headers);
    }
    public function rejectedPDF(Request $request)
    {
        $data['menu'] = 'elqc-setup';

        // Initialize the query builder
        $damageSubquery = DB::table('tbl_factory_el_qc_defect_laravel as d2')
      ->selectRaw('SUM(cell_qty)')
      ->whereColumn('d2.elqcId', 'elqc.elqc_id');

      // 2. Build the main query
      $query = DB::table('tbl_factory_el_qc_laravel as elqc')
          ->select([
              'elqc.*',
              'psl.wattage',
              'psml.size as cellSize',
              'bol.bushing_id',
              'sh.shift as shiftdtl',
              'a.fullname as elqc_operator_name', // Avoid alias collision with original column
              'b.fullname as elqc_incharge_name',
              'c.fullname as createdByName',
          ])
          ->selectSub($damageSubquery, 'no_of_cell_damage') // Injects the subquery
          ->leftJoin('hr_mstr_shift as sh', 'sh.id', '=', 'elqc.elqc_shift')
          ->join('tbl_factory_bushing_laravel as bol', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
          ->leftJoin('tbl_factory_production_setup_laravel as psl', 'psl.batchNo', '=', 'bol.bushing_batchNo')
          ->leftJoin('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
          ->leftJoin('mstr_emp as a', 'elqc.elqc_operator', '=', 'a.id')
          ->leftJoin('mstr_emp as b', 'elqc.elqc_incharge', '=', 'b.id')
          ->leftJoin('mstr_emp as c', 'elqc.created_by', '=', 'c.id');

      // 3. Apply Base Conditions (Initial logical group)
    //   $query->where(function ($q) {
    //       $q->whereNull('elqc.elqc_bushingNo')
    //         ->orWhere(function ($sub) {
    //             $sub->where('elqc.rwrk_status', '')
    //                 ->where('elqc.status', '0');
    //         });
    //   })->where('bol.bushing_hasDamage', 'No');

      // 4. Apply Dynamic Filters (Using $request instead of $_GET)
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
      $query->where('elqc.status', '=', 2);

      // 5. Final Execution with Pagination
      $lists = $query->groupBy('elqc.elqc_id')
      ->orderBy('elqc.created_at', 'DESC')
        ->get();


        $data = [
            'title' => 'Rejected ELQC Report',
            'date' => date('m/d/Y'),
            'lists' => $lists
        ];

        // If you decide to use DomPDF (standard):
        $pdf = Pdf::loadView('ProductionLineUp.ElQC.rejected_pdf', $data)->setPaper('a3', 'landscape');
        return $pdf->download('rejected_report.pdf');
            
    }
    

    public function elqcAll()
    {
        $data['menu'] = 'elqc-all';

        $Cond = [];
        $Condition = "(
            elqc.elqc_bushingNo IS NULL
            OR (elqc.rwrk_status = '' AND elqc.status = '0')
        ) 
        AND bol.bushing_hasDamage = 'No'";


        if (isset($_GET['createdBy']) && $_GET['createdBy'] != '') {
            $Cond[] = "elqc.created_by = '" . $_GET['createdBy'] . "'";
        }
        if (isset($_GET['operator']) && $_GET['operator'] != '') {
            $Cond[] = "elqc.elqc_operator = '" . $_GET['operator'] . "'";
        }
        if (isset($_GET['checker']) && $_GET['checker'] != '') {
            $Cond[] = "elqc.elqc_incharge = '" . $_GET['checker'] . "'";
        }
        if (isset($_GET['shift']) && $_GET['shift'] != '') {
            $Cond[] = "elqc.elqc_shift = '" . $_GET['shift'] . "'";
        }
        if (isset($_GET['fromDate']) && $_GET['fromDate'] != '') {
            $Cond[] = "CAST(elqc.created_at AS DATE) >= '" . $_GET['fromDate'] . "'";
        }
        if (isset($_GET['toDate']) && $_GET['toDate'] != '') {
            $Cond[] = "CAST(elqc.created_at AS DATE) <= '" . $_GET['toDate'] . "'";
        }
        if (isset($_GET['batchNo']) && $_GET['batchNo'] != '') {
            $Cond[] = "elqc.elqc_batchNo = '" . $_GET['batchNo'] . "'";
        }
        if (count($Cond) > 0) {
            $Condition = $Condition . ' AND ' . implode(' AND ', $Cond);
        }
        $sql = "SELECT 
            bol.*,
            elqc.elqc_id,elqc.rwrk_status,elqc.status,elqc.elqc_source,
            psl.wattage,
            psml.size AS cellSize,
            sh.shift AS shiftdtl,
            a.fullname AS bushing_operator,
            b.fullname AS bushing_incherge,
            c.fullname AS createdBy
            FROM tbl_factory_bushing_laravel AS bol
            LEFT JOIN tbl_factory_el_qc_laravel AS elqc
            ON bol.bushing_id = elqc.elqc_bushingNo
            LEFT JOIN hr_mstr_shift AS sh
                ON sh.id = bol.bushing_shift
            LEFT JOIN tbl_factory_production_setup_laravel AS psl 
                ON psl.batchNo = bol.bushing_batchNo
            LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
                ON psml.batchNo = psl.batchNo
            LEFT JOIN mstr_emp AS a 
                ON bol.bushing_operator = a.id
            LEFT JOIN mstr_emp AS b
                ON bol.bushing_incherge = b.id
            LEFT JOIN mstr_emp AS c
                ON bol.created_by = c.id
            WHERE $Condition
            GROUP BY bol.bushing_id 
            ORDER BY bol.created_at DESC";
        // dd($sql);
        $data['AllLists'] = DB::select($sql);


        $sql = "SELECT 
        elqc.*,
        psl.wattage,
        psml.size AS cellSize,
        bol.bushing_id,
        sh.shift AS shiftdtl,
        a.fullname AS elqc_operator,
        b.fullname AS elqc_incharge,
        c.fullname AS createdBy,
        (SELECT SUM(d2.cell_qty)
         FROM tbl_factory_el_qc_defect_laravel d2
         WHERE d2.elqcId = elqc.elqc_id
        ) AS no_of_cell_damage
        FROM tbl_factory_el_qc_laravel AS elqc
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = elqc.elqc_shift
        JOIN tbl_factory_bushing_laravel AS bol 
            ON bol.bushing_id = elqc.elqc_bushingNo
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = bol.bushing_batchNo
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON elqc.created_by = c.id
        GROUP BY elqc.elqc_id
        ORDER BY elqc.created_at DESC";
        // dd($sql);
        $data['AllELQCLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.elqc-all', $data);
    }

    public function add_el_qc()
    {
        $data['menu'] = 'elqc-setup';
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
            ->where('mstr_emp.status', '1')
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
        return view('ProductionLineUp.ElQC.add_el_qc', $data);
    }

    public function view_el_qc($id)
    {
        $data['menu'] = 'elqc-rework';
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
        $data['elqcDetails'] = DB::table('tbl_factory_el_qc_laravel as elqc')
            ->leftJoin('mstr_emp as emp1', 'elqc.elqc_operator', '=', 'emp1.id')
            ->leftJoin('mstr_emp as emp2', 'elqc.elqc_incharge', '=', 'emp2.id')
            ->leftJoin('hr_mstr_shift as sh', 'elqc.elqc_shift', '=', 'sh.id')
            ->select('elqc.*', 'emp1.fullname as operator_name', 'emp2.fullname as incharge_name', 'sh.shift as shift_name')
            ->where('elqc.elqc_id', $id)
            ->first();
        $data['defectDetails'] = DB::table('tbl_factory_el_qc_defect_laravel as def')
            ->select('def.*', 'emp.fullname as responsible_person')
            ->leftJoin('mstr_emp as emp', 'def.res_prsn', '=', 'emp.id')
            ->where('def.elqcId', $id)
            ->get();

        $data['elqcHistory'] = DB::table('tbl_factory_el_qc_history_laravel as history')
            ->select('history.*', 'emp.fullname as created_by')
            ->leftJoin('mstr_emp as emp', 'history.created_by', '=', 'emp.id')
            ->where('history.el_qc_id', $id)
            ->get();
            
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $data['elqcDetails']->elqc_batchNo)
            ->first();

        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_view', $data);
    }

    public function store_el_qc(Request $request)
    {
        $Bexists = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_barCode', $request->input('barCode'))
            ->where('bushing_batchNo', $request->input('batchNo'))
            ->exists();
        
        if($Bexists == true){
            $exists = DB::table('tbl_factory_el_qc_laravel')
          ->where('elqc_barcode', request()->input('barCode'))
          ->exists();
          
            if($exists == false){
                // dd($request->all());
                $bushId = DB::table('tbl_factory_bushing_laravel')
                    ->where('bushing_barCode', $request->input('barCode'))
                    ->where('bushing_batchNo', $request->input('batchNo'))
                  ->value('bushing_id');
                $id = date('YmdHis');
                $data = array(
                    'elqc_id' => $id,
                    'elqc_date' => date('d-m-Y'),
                    'elqc_time' => date('H:i:s'),
                    'elqc_operator' => $request->input('operator'),
                    'elqc_source' => 'Layout',
                    'elqc_bushingNo' => $bushId,
                    'elqc_batchNo' => $request->input('batchNo'),
                    'elqc_incharge' => $request->input('incharge'),
                    'elqc_shift' => $request->input('shift'),
                    'elqc_plant' => $request->input('plant'),
                    'status' => $request->input('el_type'),
                    // 'rwrk_status' => ($request->input('el_type') == '1') ? '1' : '',
                    'rwrk_status' => '1',
                    'elqc_rfid' => $request->input('rfid'),
                    'elqc_barcode' => $request->input('barCode'),
                    'created_by' => $request->session()->get('empId')
                );
        
                $res = EL_QC::create($data);
                EL_QC_History::create([
                    'el_qc_id' => $id,
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
                            'elqcId' => $id,
                            'cell_no' => $cell_no,
                            'cell_qty' => $cell_qtys[$i] ?? null,
                            'defectRsn' => $dmgMat_reasons[$i] ?? null,
                            'defectCatgry' => $defect_categories[$i] ?? null,
                            'res_prsn' => $res_prsns[$i] ?? null,
                            'res_machine' => $res_machines[$i] ?? null,
                            'status' => '0'
                        );
        
                        EL_QC_Defect::create($defectData);
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
                        $url = 'production-lineup/el-qc-add?page=ALL&lock=1&batchNo=' . $batchNo . '&operator=' . $oprtr . '&shift=' . $shift . '&incharge=' . $incherge . '&plant=' . $plant;
                        return redirect($url)->with('success', 'El QC data stored successfully!');
                    } else {
                        return redirect('production-lineup/el_qc')->with('success', 'El QC data stored successfully!');
                    }
                }
            }else{
                return redirect('production-lineup/el_qc')->with('success', 'El QC data stored failed! Duplicate Barcode.');
            }
        }else{
            return redirect('production-lineup/el_qc')->with('success', 'El QC data stored failed! Barcode not Passed in Layout Setup.');
        }
    }
    // public function getBushingMaterial(Request $request)
    // {
    //     $bushingno = $request->input('q');
    //     // Get batch no & logo once
    //     $bushing = DB::table('tbl_factory_bushing_laravel')
    //         ->where('bushing_id', $bushingno)
    //         ->select('bushing_batchNo', 'bushing_logo')
    //         ->first();

    //     if (!$bushing) {
    //         return response()->json(['message' => 'Bushing not found'], 404);
    //     }

    //     // Get materials
    //     $bushingMaterial = DB::table('tbl_factory_production_setup_laravel as psl')
    //         ->join('tbl_factory_production_setup_material_laravel as psml', 'psml.batchNo', '=', 'psl.batchNo')
    //         ->join('tbl_factory_material_master_laravel as m', 'm.id', '=', 'psml.material')
    //         ->where('psl.batchNo', $bushing->bushing_batchNo)
    //         ->select(
    //             'm.id as matid',
    //             'm.title as matname',
    //             'psml.size as msize',
    //             'psml.brand as mbrand',
    //             'psl.wattage'
    //         )
    //         ->get();

    //     $materials = [];

    //     foreach ($bushingMaterial as $item) {
    //         $materials[] = [
    //             'matid'   => $item->matid,
    //             'matname' => $item->matname,
    //             'msize'    => $item->msize ?? 'N/A',
    //             'mbrand'   => $item->mbrand ?? 'N/A',
    //         ];
    //     }

    //     return response()->json([
    //         'batchno'       => $bushing->bushing_batchNo,
    //         'bushing_logo' => $bushing->bushing_logo,
    //         'wattage'      => $bushingMaterial->first()->wattage ?? 'N/A',
    //         'materials'    => $materials
    //     ]);
    // }
    
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

    public function validateRFID(Request $request)
    {
        $rfid = $request->input('rfid');
        $batchNo = $request->input('id');
        $exists = DB::table('tbl_factory_bushing_laravel')
            ->where('bushing_rfid', $rfid)
            ->where('bushing_batchNo', $batchNo)
            ->get();

        if ($exists->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'RFID is not valid against this batchno.',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'RFID is valid.',
            ]);
        }

        return response()->json(['exists' => $exists]);
    }

    public function validateBarCode(Request $request)
    {
        $barcode = $request->get('barCode');
        $batchNo = $request->get('id');
        $action = $request->get('action') ?? '';
        $btch_viewNo = $request->get('batchNo') ?? '';
        
        if ($action === 'view') {
            $exists = DB::table('tbl_factory_bushing_laravel')
            ->select('bushing_logo')
            ->where('bushing_barCode', $barcode)
            ->where('bushing_batchNo', $btch_viewNo)
            //->where('bushing_id', $batchNo)
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
            
        // Check if barcode already used in EL QC
        $elQcExists = DB::table('tbl_factory_el_qc_laravel')
            ->where('elqc_barcode', $barcode)
            ->where('elqc_batchNo', $batchNo)
            ->exists();
    
        // If found in EL QC - INVALID
        if ($elQcExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barcode already used in EL QC.',
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

    public function el_qc_rework()
    {
        $data['menu'] = 'elqc-rework';

        $sql = "SELECT 
    elqc.*,
    psl.wattage,
    psml.size AS cellSize,
    bol.bushing_batchNo,
    bol.bushing_barCode, 
    bol.bushing_rfid,
    COALESCE(defect_sum.total_cell_damage, 0) AS no_of_cell_damage,
    sh.shift AS shiftdtl,
    a.fullname AS elqc_operator,
    b.fullname AS elqc_incharge,
    c.fullname AS createdBy
FROM tbl_factory_el_qc_laravel AS elqc
-- Optimized: Calculate defect sums in one pass
LEFT JOIN (
    SELECT elqcId, SUM(cell_qty) AS total_cell_damage
    FROM tbl_factory_el_qc_defect_laravel
    GROUP BY elqcId
) AS defect_sum ON defect_sum.elqcId = elqc.elqc_id
LEFT JOIN hr_mstr_shift AS sh 
    ON sh.id = elqc.elqc_shift
LEFT JOIN tbl_factory_bushing_laravel AS bol 
    ON bol.bushing_batchNo = elqc.elqc_batchNo
LEFT JOIN tbl_factory_production_setup_laravel AS psl 
    ON psl.batchNo = bol.bushing_batchNo
LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
    ON psml.batchNo = psl.batchNo
LEFT JOIN mstr_emp AS a 
    ON elqc.elqc_operator = a.id 
LEFT JOIN mstr_emp AS b 
    ON elqc.elqc_incharge = b.id
LEFT JOIN mstr_emp AS c 
    ON elqc.created_by = c.id
WHERE elqc.status = '0' 
  AND elqc.rwrk_status = '1'
GROUP BY elqc.elqc_id 
ORDER BY elqc.created_at DESC";
        // dd($sql);
        $data['AllELQCReworkLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_rework', $data);
    }

    public function el_qc_damage()
    {
        $data['menu'] = 'elqc-damage';

        $sql = "SELECT 
        elqc.*,
        def.*,
        psl.wattage,
        psml.size AS cellSize,
        sh.shift AS shiftdtl,
        a.fullname AS elqc_operator,
        b.fullname AS elqc_incharge,
        c.fullname AS createdBy,
        d.fullname AS rsponsible_person,
        (SELECT SUM(d2.cell_qty)
        FROM tbl_factory_el_qc_defect_laravel d2
        WHERE d2.elqcId = elqc.elqc_id
        ) AS no_of_cell_damage
        FROM tbl_factory_el_qc_laravel AS elqc
        JOIN tbl_factory_el_qc_defect_laravel AS def
            ON elqc.elqc_id = def.elqcId
        LEFT JOIN hr_mstr_shift AS sh 
            ON sh.id = elqc.elqc_shift
        LEFT JOIN tbl_factory_production_setup_laravel AS psl 
            ON psl.batchNo = elqc.elqc_batchNo
        LEFT JOIN tbl_factory_production_setup_material_laravel AS psml 
            ON psml.batchNo = psl.batchNo
        LEFT JOIN mstr_emp AS a 
            ON elqc.elqc_operator = a.id 
        LEFT JOIN mstr_emp AS b 
            ON elqc.elqc_incharge = b.id
        LEFT JOIN mstr_emp AS c 
            ON elqc.created_by = c.id
        LEFT JOIN mstr_emp AS d 
            ON def.res_prsn = d.id
        WHERE elqc.status = '0'
        GROUP BY def.def_id
        ORDER BY def.def_id DESC";

        // dd($sql);
        $data['AllELQCDamageLists'] = DB::select($sql);
        
        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.ElQC.el_qc_damage', $data);
    }

    public function update_el_qc(Request $request, $id)
    {
        $rwrk_status = $request->input('rwrk_status');
        $rwrk_pg = $request->input('rwrk_pg');
        if($rwrk_pg){
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '1']);
                DB::table('tbl_factory_el_qc_defect_laravel')->where('elqcId', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '1', 'status' => '0']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'EL Damage',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                // EL_QC_Defect::where('elqcId', $id)->delete();
            
                $cell_positions = $request->input('cell_position', []);
                $cell_qtys = $request->input('cell_qty', []);
                $dmgMat_reasons = $request->input('dmgMat_reason', []);
                $defect_categories = $request->input('dmgMat_cat', []);
                $res_prsns = $request->input('res_prsn', []);
                $res_machines = $request->input('res_machine', []);
    
                if (is_array($cell_positions) && count($cell_positions) > 0) {
                    foreach ($cell_positions as $i => $cell_no) {
                        // skip empty rows (optional)
                        if ($cell_no === null || $cell_no === '') {
                            continue;
                        }
    
                        $defectData = array(
                            'elqcId' => $id,
                            'cell_no' => $cell_no,
                            'cell_qty' => $cell_qtys[$i] ?? null,
                            'defectRsn' => $dmgMat_reasons[$i] ?? null,
                            'defectCatgry' => $defect_categories[$i] ?? null,
                            'res_prsn' => $res_prsns[$i] ?? null,
                            'res_machine' => $res_machines[$i] ?? null,
                            'status' => '0'
                        );
    
                        EL_QC_Defect::create($defectData);
                    }
                }
            }
        } else {
            if ($rwrk_status == '1') {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '', 'status' => '0']);
                DB::table('tbl_factory_el_qc_defect_laravel')->where('elqcId', $id)
                    ->update(['status' => '1']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Rework Passed',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                // EL_QC_Defect::where('elqcId', $id)->delete();
            
                $cell_positions = $request->input('cell_position', []);
                $cell_qtys = $request->input('cell_qty', []);
                $dmgMat_reasons = $request->input('dmgMat_reason', []);
                $defect_categories = $request->input('dmgMat_cat', []);
                $res_prsns = $request->input('res_prsn', []);
                $res_machines = $request->input('res_machine', []);
    
                if (is_array($cell_positions) && count($cell_positions) > 0) {
                    foreach ($cell_positions as $i => $cell_no) {
                        // skip empty rows (optional)
                        if ($cell_no === null || $cell_no === '') {
                            continue;
                        }
    
                        $defectData = array(
                            'elqcId' => $id,
                            'cell_no' => $cell_no,
                            'cell_qty' => $cell_qtys[$i] ?? null,
                            'defectRsn' => $dmgMat_reasons[$i] ?? null,
                            'defectCatgry' => $defect_categories[$i] ?? null,
                            'res_prsn' => $res_prsns[$i] ?? null,
                            'res_machine' => $res_machines[$i] ?? null,
                            'status' => '0'
                        );
    
                        EL_QC_Defect::create($defectData);
                    }
                }
            } else {
                DB::table('tbl_factory_el_qc_laravel')
                    ->where('elqc_id', $id)
                    ->update(['elqc_source' => 'ReWork', 'rwrk_status' => '2', 'status' => '2']);
                DB::table('tbl_factory_el_qc_history_laravel')->insert([
                    'el_qc_id' => $id,
                    'action' => 'Reject',
                    'ip_address' => $this->getUserIP(),
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        if($rwrk_pg){
        return redirect('production-lineup/el_qc')->with('success', 'El QC rework status updated successfully!');
        } else {
        return redirect('production-lineup/el_qc_rework')->with('success', 'El QC rework status updated successfully!');
        }
    }

    public function getBushingId(Request $request)
    {
        $batchNo = $request->input('q');

        $bushingId = DB::table('tbl_factory_bushing_laravel as bol')
            ->leftJoin('tbl_factory_el_qc_laravel as elqc', 'bol.bushing_id', '=', 'elqc.elqc_bushingNo')
            ->select('bol.bushing_id')
            ->where('bol.bushing_batchNo', $batchNo)
            ->where(function ($query) {
                $query->whereNull('elqc.elqc_bushingNo')
                    ->where('bol.bushing_hasDamage', 'No');
            })
            ->get();

        return response()->json(['bushingIds' => $bushingId]);
    }


    public function getDefBatchId(Request $request)
    {
        $batchNo = $request->input('q');
        $data['bushingMaterial'] = DB::table('tbl_factory_production_setup_laravel as psl')
            ->select('psl.cellRow', 'psl.celColumn')
            ->where('psl.batchNo', $batchNo)
            ->first();
        return response()->json(['defBatchId' => $data['bushingMaterial']]);
    }
    
    public function fetchRFIDBar(Request $request)
    {
        $batchNo = $request->input('batch_No');
        $bushingNo = $request->input('bushingNo');

        $RFIDBardtls = DB::table('tbl_factory_bushing_laravel as bol')
            ->select('bol.bushing_rfid','bol.bushing_barCode')
            ->where('bol.bushing_batchNo', $batchNo)
            ->where('bol.bushing_id', $bushingNo)
            ->first();

        return response()->json(['rfid' => $RFIDBardtls->bushing_rfid, 'barcode' => $RFIDBardtls->bushing_barCode]);
    }
}
