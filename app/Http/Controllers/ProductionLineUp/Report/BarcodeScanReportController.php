<?php

namespace App\Http\Controllers\ProductionLineUp\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarcodeScanReportController extends Controller
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
    public function index(Request $request)
    {
        $data['menu'] = 'barcode-scan-report';
        $data['empId'] = request()->session()->get('empId');

        //$fromDate = request()->query('fdate');
        //$toDate = request()->query('tdate');

        $query = DB::table('tbl_factory_fqc_laravel')
            ->join('tbl_factory_jb_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_jb_laravel.jb_barcode')
            ->join('tbl_factory_ninetydeg_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_ninetydeg_laravel.ninetydeg_barcode')
            ->join('tbl_factory_el_qc_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_el_qc_laravel.elqc_barcode')
            ->join('tbl_factory_bushing_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_bushing_laravel.bushing_barCode')
            ->select('tbl_factory_fqc_laravel.*', 'tbl_factory_bushing_laravel.scan_flag AS bushFlag', 'tbl_factory_el_qc_laravel.scan_flag AS elqcFlag', 'tbl_factory_ninetydeg_laravel.scan_flag AS ninetyFlag', 'tbl_factory_jb_laravel.scan_flag AS jbflag');

        if ($request->filled('fdate')) {
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) >= ?", [$request->input('fdate')]);
        }else{
           $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) >= ?", date('Y-m-d', strtotime('-30 days')));
        }
    
        if ($request->filled('tdate')) {
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) <= ?", [$request->input('tdate')]);
        }else{
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) <= ?", date('Y-m-d'));
        }
        
        if ($request->filled('barcode')) {
            $query->where("tbl_factory_fqc_laravel.fqc_barcode", [$request->input('barcode')]);
        }
        
        $query->groupBy("tbl_factory_fqc_laravel.fqc_barcode");
        $query->orderBy("tbl_factory_fqc_laravel.created_at", 'DESC');
        //dd($query);
        // 4. Paginate the results (e.g., 15 items per page)
        
        $perPage = $request->input('per_page', 10);
        $data['AllLists'] = $query->paginate($perPage);
        $data['perPage'] = $perPage;
        // 5. Append query parameters so pagination links don't lose the date filters
        //$results->appends(['fromDate' => $fromDate, 'toDate' => $toDate]);


        $data['PermittedMenuList'] = self::PermittedMenuList(request()->session()->get('empId'));
        return view('ProductionLineUp.Report.barcode-scan-report', $data);
    }
    
    
    public function excelDownload(Request $request)
    {
        

        $query = DB::table('tbl_factory_fqc_laravel')
            ->join('tbl_factory_jb_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_jb_laravel.jb_barcode')
            ->join('tbl_factory_ninetydeg_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_ninetydeg_laravel.ninetydeg_barcode')
            ->join('tbl_factory_el_qc_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_el_qc_laravel.elqc_barcode')
            ->join('tbl_factory_bushing_laravel', 'tbl_factory_fqc_laravel.fqc_barcode', '=', 'tbl_factory_bushing_laravel.bushing_barCode')
            ->select('tbl_factory_fqc_laravel.*', 'tbl_factory_bushing_laravel.scan_flag AS bushFlag', 'tbl_factory_el_qc_laravel.scan_flag AS elqcFlag', 'tbl_factory_ninetydeg_laravel.scan_flag AS ninetyFlag', 'tbl_factory_jb_laravel.scan_flag AS jbflag');

        if ($request->filled('fdate')) {
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) >= ?", [$request->input('fdate')]);
        }else{
           $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) >= ?", date('Y-m-d', strtotime('-30 days')));
        }
    
        if ($request->filled('tdate')) {
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) <= ?", [$request->input('tdate')]);
        }else{
            $query->whereRaw("CAST(tbl_factory_fqc_laravel.created_at AS DATE) <= ?", date('Y-m-d'));
        }
        
        if ($request->filled('barcode')) {
            $query->where("tbl_factory_fqc_laravel.fqc_barcode", [$request->input('barcode')]);
        }
        
        $query->groupBy("tbl_factory_fqc_laravel.fqc_barcode");
        $query->orderBy("tbl_factory_fqc_laravel.created_at", 'DESC');
        
        $AllLists = $query->get();

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
            'FQC Date',
            'Barcode',
            'Lay Up',
            'EL QC',
            '90 Degree QC',
            'Junction Box',
            'Final QC',
            'Total'
        ];

        $callback = function () use ($AllLists, $columns) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $columns);

            foreach ($AllLists as $key => $row) {

                fputcsv($file, [
                    $key + 1,
                    $row->created_at,
                    $row->fqc_barcode,
                    ($row->bushFlag==1)? 'YES' : 'NO',
                    ($row->elqcFlag==1)? 'YES' : 'NO',
                    ($row->ninetyFlag==1)? 'YES' : 'NO',
                    ($row->jbflag==1)? 'YES' : 'NO',
                    ($row->scan_flag==1)? 'YES' : 'NO',
                    $row->bushFlag + $row->elqcFlag + $row->ninetyFlag + $row->jbflag + $row->scan_flag
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
