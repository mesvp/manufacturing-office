<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\PlantStock;
use App\Models\MaterialManagement\MaterialManagement_Add_Material;
use App\Models\Master\{Prj_Subproject, Prj_Project, Dd_crmwtp_dispatch_item, Dd_crmwtp_dispatch_detail};
use App\Models\Master\RawMaterial\{Master_Godown_Name, Master_materialDetailsGatepass, Master_Raw_Material, Master_Raw_Material_Detail};
use App\Models\GatePass\OutGatepassItemDetails;
use App\Models\{CheckBox, Admin, Forwarded_Data, Department_Assign};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductionProcess\{Production_Process, Production_Process_Machine, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom, prj_organisation, Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery, Module_Bsns_Unit, Prj_Inventory, Pur_Address};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Production\{Production_For_Sales, Production_For_Stock, ProductionData, ProductionBatch, Production, ProductionApprove};
use App\Models\Storeissue\{Store_issue, StoreIssueApprove, StoreIssueApprovedMaterial};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer, Mrn_Stock_Transfer_Detail, Mrn_Stock_Transfer_Approve};
use App\Models\FinishedGood\{FinishedGoodGatepass, Finished_good_gatepasses_detail};

class SerialNumberController extends Controller
{

    public function list(Request $request)
    {
        try {
            \Log::info('=== SERIAL NUMBER REPORT REQUEST ===');
            \Log::info('Request parameters:', $request->all());
            
            $fromdate = $request->input('from_date');
            $dateto = $request->input('to_date');
            
            // Get search term from request (used instead of serial_number dropdown)
            $searchTerm = $request->input('search', '');
            
            // Get other filters
            $statusFilter = $request->status;
            $materialNameFilter = $request->material_name;
            $sourceFilter = $request->source;

            // Add validation to restrict date range to maximum 1 month
            // BUT completely bypass this restriction if searching via search field
            if (!empty($fromdate) && !empty($dateto) && (empty($searchTerm) || trim($searchTerm) === '')) {
                $from = \Carbon\Carbon::parse($fromdate);
                $to = \Carbon\Carbon::parse($dateto);
                
                $daysDiff = $to->diffInDays($from);
                
                // Only show error if no search term entered AND date range exceeds 30 days
                if ($daysDiff > 30) {
                    \Log::warning('Date range exceeded 30 days:', ['from' => $fromdate, 'to' => $dateto, 'days' => $daysDiff]);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Please select a date range of maximum 1 month (30 days) for better performance. Note: You can search beyond 1 month by using the search field.');
                }
            }

            // Set default 1-day date range if no dates provided
            if (empty($fromdate) && empty($dateto)) {
                // Default to today only
                $fromdate = \Carbon\Carbon::now()->format('Y-m-d');
                $dateto = \Carbon\Carbon::now()->format('Y-m-d');
            }

            // If only one date is provided, set the other to match
            if (!empty($fromdate) && empty($dateto)) {
                $dateto = $fromdate;
            }

            if (empty($fromdate) && !empty($dateto)) {
                $fromdate = $dateto;
            }

            // Format dates consistently
            $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
            $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');
            
            \Log::info('Processing dates:', ['from' => $fromdate, 'to' => $dateto, 'search' => $searchTerm]);
            
            // Get paginated results using Laravel's built-in pagination
            // Pass null for serialNumberFilter since we're using searchTerm instead
            $availableSerials = $this->getAllAvailableSerialsPaginated($fromdate, $dateto, $statusFilter, $materialNameFilter, null, $sourceFilter, $searchTerm);
            
            \Log::info('Query results:', ['total' => $availableSerials->total(), 'count' => $availableSerials->count()]);

            // Get lightweight filter options using optimized query
            // $allFilterOptions = $this->getFilterOptions();
            
            // Get only distinct materials
            $materials = DB::table('prj_material')
                ->select('material_name')
                ->whereNotNull('material_name')
                ->distinct()
                ->orderBy('material_name')
                ->get();
            
            // Fixed source list
            $sources = collect([
                (object)['source' => 'Production'],
                (object)['source' => 'MRN Stock Transfer'],
                (object)['source' => 'Manual FG Entry'],
            ]);
            
            // \Log::info('Filter options count:', ['count' => count($allFilterOptions)]);
            
            \Log::info('Filter options count:', [
                'materials' => $materials->count(),
                'sources' => $sources->count()
            ]);


            return view('Report/sl_no_avlbl-report', [
                'AvailableSerials' => $availableSerials,
                // 'AllFilterOptions' => $allFilterOptions,
                'materials' => $materials,
                'sources' => $sources,
                'searchTerm' => $searchTerm,
                'fromdate' => $fromdate,
                'todate' => $dateto
            ]);
        } catch (\Exception $e) {
            \Log::error('Serial Number Report Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Error loading report: ' . $e->getMessage());
        }
    }
     public function dispatchlist(Request $request)
    {
        try {
            $fromdate = $request->input('from_date');
            $dateto = $request->input('to_date');
            $searchTerm = $request->input('search', '');
            $statusFilter = $request->status;
            $materialIdFilter = $request->input('material_id');
            $sourceFilter = $request->source;

            if (empty($fromdate) && empty($dateto)) {
                $fromdate = \Carbon\Carbon::now()->format('Y-m-d');
                $dateto = \Carbon\Carbon::now()->format('Y-m-d');
            }

            if (!empty($fromdate) && empty($dateto)) {
                $dateto = $fromdate;
            }

            if (empty($fromdate) && !empty($dateto)) {
                $fromdate = $dateto;
            }

            $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
            $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');

            $materialOptions = DB::table('crmwtp_product_details')
                ->select('id', 'model_name')
                ->whereNotNull('model_name')
                ->orderBy('model_name')
                ->distinct()
                ->get();

            $dispatchedSerials = $this->getAllDispatchedSerialsPaginated($fromdate, $dateto, $statusFilter, $materialIdFilter, null, $sourceFilter, $searchTerm);

            return view('Report/dis_sl_no_avlbl-report', [
                'AvailableSerials' => $dispatchedSerials,
                'searchTerm' => $searchTerm,
                'fromdate' => $fromdate,
                'todate' => $dateto,
                'statusFilter' => $statusFilter,
                'materialOptions' => $materialOptions,
                'materialIdFilter' => $materialIdFilter,
            ]);
        } catch (\Exception $e) {
            \Log::error('Dispatch Serial List Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Error loading dispatch report: ' . $e->getMessage());
        }
    }
     public function wiplist(Request $request)
    {
        $fromdate = $request->input('from_date');
        $dateto = $request->input('to_date');
        
        // Get search term from request
        $searchTerm = $request->input('search', '');
        
        // Get other filters
        $statusFilter = $request->status;
        $organizationFilter = $request->organization_name;
        $shiftNameFilter = $request->shift_name;
        $shiftNameNmFilter = $request->shift_name_nm;

        // Add validation to restrict date range to maximum 3 months
        // BUT completely bypass this restriction if searching via search field
        if (!empty($fromdate) && !empty($dateto) && (empty($searchTerm) || trim($searchTerm) === '')) {
            $from = \Carbon\Carbon::parse($fromdate);
            $to = \Carbon\Carbon::parse($dateto);
        
        }

        // Set default 3-month date range from current date if no dates provided
        // if (empty($fromdate) && empty($dateto)) {
        //     // Default to exactly 90 days (3 months) from current date
        //     $fromdate = \Carbon\Carbon::now()->subDays(90)->format('Y-m-d');
        //     $dateto = \Carbon\Carbon::now()->format('Y-m-d');
        // }

        // If only one date is provided, set the other to match
        if (!empty($fromdate) && empty($dateto)) {
            $dateto = $fromdate;
        }

        if (empty($fromdate) && !empty($dateto)) {
            $fromdate = $dateto;
        }

        // Format dates consistently if they exist
        if ($fromdate) {
            $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
        }
        if ($dateto) {
            $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');
        }
        
        // Get paginated WIP serial numbers
        $wipSerials = $this->getWipSerialsPaginated($fromdate, $dateto, $statusFilter, $organizationFilter, $shiftNameFilter, $shiftNameNmFilter, $searchTerm);

        // Get lightweight filter options
        $allFilterOptions = $this->getWipFilterOptions();

        return view('Report/wip_sl_no_avlbl-report', [
            'WipSerials' => $wipSerials,
            'AllFilterOptions' => $allFilterOptions,
            'searchTerm' => $searchTerm,
            'fromdate' => $fromdate,
            'todate' => $dateto
        ]);
    }
     /**
     * Get all available serial numbers from all inward sources that are not dispatched
     */
    private function getAllAvailableSerials($fromdate, $dateto, $statusFilter = null, $materialNameFilter = null, $serialNumberFilter = null, $sourceFilter = null)
    {
        // Get all received serial numbers from production_batch (regardless of approval)
         $productionSerialsQuery = DB::table('production_batch')
            ->join('production', 'production_batch.productionID', '=', 'production.id')
            ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'production_batch.serail_check as serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("COALESCE(production_batch.batch_no, 'N/A') as batch_no"),
                'production.Production_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(production.Approve_status, 'PENDING') as status"),
                'production.Production_Date as transaction_date',
                DB::raw("'Production' as source"),
                'production.id as source_id'
            )
            ->whereNotNull('production_batch.serail_check')
            ->where('production_batch.serail_check', '!=', '');
            
        // Always apply date range filter (matching paginated version)
        if (!empty($fromdate) && !empty($dateto)) {
            $productionSerialsQuery->whereBetween('production.Production_Date', [$fromdate, $dateto]);
        }

        // Apply filters for production
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $productionSerialsQuery->where(function($query) {
                    $query->whereNull('production.Approve_status')
                          ->orWhere('production.Approve_status', '')
                          ->orWhere('production.Approve_status', 'PENDING');
                });
            } else {
                $productionSerialsQuery->where('production.Approve_status', $statusFilter);
            }
        }
        if ($materialNameFilter) {
            $productionSerialsQuery->where('prj_material.material_name', 'LIKE', '%' . $materialNameFilter . '%');
        }
        if ($serialNumberFilter) {
            $productionSerialsQuery->where(DB::raw('LOWER(production_batch.serail_check)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
        }
        if ($sourceFilter) {
            $productionSerialsQuery->havingRaw("'Production' LIKE ?", ['%' . $sourceFilter . '%']);
        }

        // Get all received serial numbers from mrn_stock_transfer_details (regardless of approval)
        $mrnSerialsQuery = DB::table('mrn_stock_transfer_details')
            ->join('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
            ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
            ->select(
                'mrn_stock_transfer_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'mrn_stock_transfer.purchahedate as invoice_date',
                DB::raw("master_raw_material_details.Invoice_No as invoice_no"),
                DB::raw("COALESCE(mrn_stock_transfer.Approve_status, 'PENDING') as status"),
                'mrn_stock_transfer.purchahedate as transaction_date',
                DB::raw("'MRN Stock Transfer' as source"),
                'mrn_stock_transfer.id as source_id'
            )
            ->whereNotNull('mrn_stock_transfer_details.serial_no')
            ->where('mrn_stock_transfer_details.serial_no', '!=', '');
            
        // Always apply date range filter (matching paginated version)
        if (!empty($fromdate) && !empty($dateto)) {
            $mrnSerialsQuery->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $dateto]);
        }

        // Apply filters for MRN
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $mrnSerialsQuery->where(function($query) {
                    $query->whereNull('mrn_stock_transfer.Approve_status')
                          ->orWhere('mrn_stock_transfer.Approve_status', '')
                          ->orWhere('mrn_stock_transfer.Approve_status', 'PENDING');
                });
            } else {
                $mrnSerialsQuery->where('mrn_stock_transfer.Approve_status', $statusFilter);
            }
        }
        if ($materialNameFilter) {
            $mrnSerialsQuery->where('prj_material.material_name', 'LIKE', '%' . $materialNameFilter . '%');
        }
        if ($serialNumberFilter) {
            $mrnSerialsQuery->where(DB::raw('LOWER(mrn_stock_transfer_details.serial_no)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
        }

        // Get all received serial numbers from finished_good_gatepasses_details (manual FG entry, regardless of approval)
        $fgSerialsQuery = DB::table('finished_good_gatepasses_details')
            ->join('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
            ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'finished_good_gatepasses_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'finished_good_gatepasses.Transaction_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(finished_good_gatepasses.Approve_status, 'PENDING') as status"),
                'finished_good_gatepasses.Transaction_Date as transaction_date',
                DB::raw("'Manual FG Entry' as source"),
                'finished_good_gatepasses.id as source_id'
            )
            ->whereNotNull('finished_good_gatepasses_details.serial_no')
            ->where('finished_good_gatepasses_details.serial_no', '!=', '');
            
        // Always apply date range filter (matching paginated version)
        if (!empty($fromdate) && !empty($dateto)) {
            $fgSerialsQuery->whereBetween('finished_good_gatepasses.Transaction_Date', [$fromdate, $dateto]);
        }

        // Apply filters for FG
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $fgSerialsQuery->where(function($query) {
                    $query->whereNull('finished_good_gatepasses.Approve_status')
                          ->orWhere('finished_good_gatepasses.Approve_status', '')
                          ->orWhere('finished_good_gatepasses.Approve_status', 'PENDING');
                });
            } else {
                $fgSerialsQuery->where('finished_good_gatepasses.Approve_status', $statusFilter);
            }
        }
        if ($materialNameFilter) {
            $fgSerialsQuery->where('prj_material.material_name', 'LIKE', '%' . $materialNameFilter . '%');
        }
        if ($serialNumberFilter) {
            $fgSerialsQuery->where(DB::raw('LOWER(finished_good_gatepasses_details.serial_no)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
        }
        
        // Apply source filter at database level
        if ($sourceFilter) {
            if (stripos('Production', $sourceFilter) === false) {
                $productionSerialsQuery->whereRaw('1 = 0'); // Exclude production
            }
            if (stripos('MRN Stock Transfer', $sourceFilter) === false) {
                $mrnSerialsQuery->whereRaw('1 = 0'); // Exclude MRN
            }
            if (stripos('Manual FG Entry', $sourceFilter) === false) {
                $fgSerialsQuery->whereRaw('1 = 0'); // Exclude FG
            }
        }

        // Apply database-level filtering to exclude dispatched serials (most memory efficient)
        // Add NOT EXISTS clauses to each query to exclude dispatched serials at database level
        
        $productionSerialsQuery->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('dd_crmwtp_dispatch_items_slno')
                ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'production_batch.serail_check')
                ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
        });

        $mrnSerialsQuery->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('dd_crmwtp_dispatch_items_slno')
                ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'mrn_stock_transfer_details.serial_no')
                ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
        });

        $fgSerialsQuery->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('dd_crmwtp_dispatch_items_slno')
                ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'finished_good_gatepasses_details.serial_no')
                ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
        });

        // Create union query
        $unionQuery = $productionSerialsQuery
            ->union($mrnSerialsQuery)
            ->union($fgSerialsQuery);

        // Wrap union in subquery to apply ORDER BY correctly (same as paginated version)
        $allReceivedSerials = DB::table(DB::raw("({$unionQuery->toSql()}) as combined_results"))
            ->mergeBindings($unionQuery)
            ->orderBy('transaction_date', 'DESC')
            ->get();

        // Process results and set default values
        foreach ($allReceivedSerials as $serial) {
            $serial->matname = $serial->matname ?? 'N/A';
            $serial->HSN_Code = $serial->HSN_Code ?? 'N/A';
            $serial->UOM = $serial->UOM ?? 'N/A';
            $serial->batch_no = $serial->batch_no ?? 'N/A';
            $serial->invoice_no = $serial->invoice_no ?? 'N/A';
            $serial->status = $serial->status ?? 'PENDING';
            $serial->type = 'Available Serial';
        }
        
        // Clean up memory
        gc_collect_cycles();

        // Return collection with database ordering preserved
        return $allReceivedSerials;
    }

    /**
     * Get paginated available serial numbers using Laravel's built-in pagination
     */
    // private function getAllAvailableSerialsPaginated($fromdate, $dateto, $statusFilter = null, $materialNameFilter = null, $serialNumberFilter = null, $sourceFilter = null, $searchTerm = null)
    // {
    //     // Build the base union query for all sources
    //     $productionQuery = DB::table('production_batch')
    //         ->join('production', 'production_batch.productionID', '=', 'production.id')
    //         ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
    //         ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
    //         ->select(
    //             'production_batch.serail_check as serial_no',
    //             'prj_material.material_name as matname',
    //             'materialmanagement_add_material.HSN_Code',
    //             'materialmanagement_add_material.UOM',
    //             DB::raw("COALESCE(production_batch.batch_no, 'N/A') as batch_no"),
    //             'production.Production_Date as invoice_date',
    //             DB::raw("'N/A' as invoice_no"),
    //             DB::raw("COALESCE(production.Approve_status, 'PENDING') as status"),
    //             'production.Production_Date as transaction_date',
    //             DB::raw("'Production' as source"),
    //             'production.id as source_id'
    //         )
    //         ->whereNotNull('production_batch.serail_check')
    //         ->where('production_batch.serail_check', '!=', '')
    //         ->whereNotExists(function ($query) {
    //             $query->select(DB::raw(1))
    //                 ->from('dd_crmwtp_dispatch_items_slno')
    //                 ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
    //                 ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'production_batch.serail_check')
    //                 ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
    //         });

    //     $mrnQuery = DB::table('mrn_stock_transfer_details')
    //         ->join('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
    //         ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
    //         ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
    //         ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
    //         ->select(
    //             'mrn_stock_transfer_details.serial_no',
    //             'prj_material.material_name as matname',
    //             'materialmanagement_add_material.HSN_Code',
    //             'materialmanagement_add_material.UOM',
    //             DB::raw("'N/A' as batch_no"),
    //             'mrn_stock_transfer.purchahedate as invoice_date',
    //             DB::raw("master_raw_material_details.Invoice_No as invoice_no"),
    //             DB::raw("COALESCE(mrn_stock_transfer.Approve_status, 'PENDING') as status"),
    //             'mrn_stock_transfer.purchahedate as transaction_date',
    //             DB::raw("'MRN Stock Transfer' as source"),
    //             'mrn_stock_transfer.id as source_id'
    //         )
    //         ->whereNotNull('mrn_stock_transfer_details.serial_no')
    //         ->where('mrn_stock_transfer_details.serial_no', '!=', '')
    //         ->whereNotExists(function ($query) {
    //             $query->select(DB::raw(1))
    //                 ->from('dd_crmwtp_dispatch_items_slno')
    //                 ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
    //                 ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'mrn_stock_transfer_details.serial_no')
    //                 ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
    //         });

    //     $fgQuery = DB::table('finished_good_gatepasses_details')
    //         ->join('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
    //         ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
    //         ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
    //         ->select(
    //             'finished_good_gatepasses_details.serial_no',
    //             'prj_material.material_name as matname',
    //             'materialmanagement_add_material.HSN_Code',
    //             'materialmanagement_add_material.UOM',
    //             DB::raw("'N/A' as batch_no"),
    //             'finished_good_gatepasses.Transaction_Date as invoice_date',
    //             DB::raw("'N/A' as invoice_no"),
    //             DB::raw("COALESCE(finished_good_gatepasses.Approve_status, 'PENDING') as status"),
    //             'finished_good_gatepasses.Transaction_Date as transaction_date',
    //             DB::raw("'Manual FG Entry' as source"),
    //             'finished_good_gatepasses.id as source_id'
    //         )
    //         ->whereNotNull('finished_good_gatepasses_details.serial_no')
    //         ->where('finished_good_gatepasses_details.serial_no', '!=', '')
    //         ->whereNotExists(function ($query) {
    //             $query->select(DB::raw(1))
    //                 ->from('dd_crmwtp_dispatch_items_slno')
    //                 ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
    //                 ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'finished_good_gatepasses_details.serial_no')
    //                 ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
    //         });

    //     // Apply date filters only if provided
    //     if (!empty($fromdate) && !empty($dateto)) {
    //         $productionQuery->whereBetween('production.Production_Date', [$fromdate, $dateto]);
    //         $mrnQuery->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $dateto]);
    //         $fgQuery->whereBetween('finished_good_gatepasses.Transaction_Date', [$fromdate, $dateto]);
    //     }

    //     // Apply filters to each query
    //     $this->applyFiltersToQuery($productionQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'production');
    //     $this->applyFiltersToQuery($mrnQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'mrn');
    //     $this->applyFiltersToQuery($fgQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'fg');

    //     // Create union query and paginate
    //     $unionQuery = $productionQuery
    //         ->union($mrnQuery)
    //         ->union($fgQuery);

    //     // Use Laravel's paginate method
    //     return DB::table(DB::raw("({$unionQuery->toSql()}) as combined_results"))
    //         ->mergeBindings($unionQuery)
    //         ->orderBy('transaction_date', 'DESC')
    //         ->paginate(10)
    //         ->appends(request()->query());
    // }
     private function getAllAvailableSerialsPaginated($fromdate, $dateto, $statusFilter = null, $materialNameFilter = null, $serialNumberFilter = null, $sourceFilter = null, $searchTerm = null)
    {
        // Build the base union query for all sources
        $productionQuery = DB::table('production_batch')
            ->join('production', 'production_batch.productionID', '=', 'production.id')
            ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'production_batch.serail_check as serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("COALESCE(production_batch.batch_no, 'N/A') as batch_no"),
                'production.Production_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(production.Approve_status, 'PENDING') as status"),
                'production.Production_Date as transaction_date',
                DB::raw("'Production' as source"),
                'production.id as source_id'
            )
            ->whereNotNull('production_batch.serail_check')
            ->where('production_batch.serail_check', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'production_batch.serail_check')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        $mrnQuery = DB::table('mrn_stock_transfer_details')
            ->join('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
            ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
            ->select(
                'mrn_stock_transfer_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'mrn_stock_transfer.purchahedate as invoice_date',
                DB::raw("master_raw_material_details.Invoice_No as invoice_no"),
                DB::raw("COALESCE(mrn_stock_transfer.Approve_status, 'PENDING') as status"),
                'mrn_stock_transfer.purchahedate as transaction_date',
                DB::raw("'MRN Stock Transfer' as source"),
                'mrn_stock_transfer.id as source_id'
            )
            ->whereNotNull('mrn_stock_transfer_details.serial_no')
            ->where('mrn_stock_transfer_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'mrn_stock_transfer_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        $fgQuery = DB::table('finished_good_gatepasses_details')
            ->join('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
            ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'finished_good_gatepasses_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'finished_good_gatepasses.Transaction_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(finished_good_gatepasses.Approve_status, 'PENDING') as status"),
                'finished_good_gatepasses.Transaction_Date as transaction_date',
                DB::raw("'Manual FG Entry' as source"),
                'finished_good_gatepasses.id as source_id'
            )
            ->whereNotNull('finished_good_gatepasses_details.serial_no')
            ->where('finished_good_gatepasses_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'finished_good_gatepasses_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        // Apply date filters only if provided AND no search term is entered (bypass for direct search)
        if (!empty($fromdate) && !empty($dateto) && (empty($searchTerm) || trim($searchTerm) === '')) {
            $productionQuery->whereBetween('production.Production_Date', [$fromdate, $dateto]);
            $mrnQuery->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $dateto]);
            $fgQuery->whereBetween('finished_good_gatepasses.Transaction_Date', [$fromdate, $dateto]);
        }

        // Apply filters to each query
        $this->applyFiltersToQuery($productionQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'production');
        $this->applyFiltersToQuery($mrnQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'mrn');
        $this->applyFiltersToQuery($fgQuery, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, 'fg');

        // Create union query and paginate
        $unionQuery = $productionQuery
            ->union($mrnQuery)
            ->union($fgQuery);

        \Log::info('Union Query SQL:', ['sql' => $unionQuery->toSql()]);
        \Log::info('Union Query Bindings:', ['bindings' => $unionQuery->getBindings()]);
        
        // Use Laravel's paginate method
        try {
            $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined_results"))
                ->mergeBindings($unionQuery)
                ->orderBy('transaction_date', 'DESC')
                ->paginate(10)
                ->appends(request()->query());
                
            \Log::info('Pagination successful:', [
                'total' => $results->total(),
                'count' => $results->count(),
                'current_page' => $results->currentPage(),
                'per_page' => $results->perPage()
            ]);
            
            return $results;
        } catch (\Exception $e) {
            \Log::error('Pagination Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }
    }
    

    /**
     * Apply filters to individual queries
     */
    private function applyFiltersToQuery($query, $statusFilter, $materialNameFilter, $serialNumberFilter, $sourceFilter, $searchTerm, $queryType)
    {
        // Status filter
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                if ($queryType === 'production') {
                    $query->where(function($q) {
                        $q->whereNull('production.Approve_status')
                          ->orWhere('production.Approve_status', '')
                          ->orWhere('production.Approve_status', 'PENDING');
                    });
                } elseif ($queryType === 'mrn') {
                    $query->where(function($q) {
                        $q->whereNull('mrn_stock_transfer.Approve_status')
                          ->orWhere('mrn_stock_transfer.Approve_status', '')
                          ->orWhere('mrn_stock_transfer.Approve_status', 'PENDING');
                    });
                } elseif ($queryType === 'fg') {
                    $query->where(function($q) {
                        $q->whereNull('finished_good_gatepasses.Approve_status')
                          ->orWhere('finished_good_gatepasses.Approve_status', '')
                          ->orWhere('finished_good_gatepasses.Approve_status', 'PENDING');
                    });
                }
            } else {
                if ($queryType === 'production') {
                    $query->where('production.Approve_status', $statusFilter);
                } elseif ($queryType === 'mrn') {
                    $query->where('mrn_stock_transfer.Approve_status', $statusFilter);
                } elseif ($queryType === 'fg') {
                    $query->where('finished_good_gatepasses.Approve_status', $statusFilter);
                }
            }
        }

        // Material name filter
        if ($materialNameFilter) {
            $query->where('prj_material.material_name', 'LIKE', '%' . $materialNameFilter . '%');
        }

        // Serial number filter
        if ($serialNumberFilter) {
            if ($queryType === 'production') {
                $query->where(DB::raw('LOWER(production_batch.serail_check)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
            } elseif ($queryType === 'mrn') {
                $query->where(DB::raw('LOWER(mrn_stock_transfer_details.serial_no)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
            } elseif ($queryType === 'fg') {
                $query->where(DB::raw('LOWER(finished_good_gatepasses_details.serial_no)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
            }
        }

        // Source filter
        if ($sourceFilter) {
            $sourceMap = [
                'production' => 'Production',
                'mrn' => 'MRN Stock Transfer', 
                'fg' => 'Manual FG Entry'
            ];
            if (stripos($sourceMap[$queryType], $sourceFilter) === false) {
                $query->whereRaw('1 = 0'); // Exclude this query type
            }
        }

        // Search term filter
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm, $queryType) {
                $search = strtolower($searchTerm);
                $q->where('prj_material.material_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('materialmanagement_add_material.HSN_Code', 'LIKE', '%' . $search . '%')
                  ->orWhere('materialmanagement_add_material.UOM', 'LIKE', '%' . $search . '%');
                
                if ($queryType === 'production') {
                    $q->orWhere('production_batch.serail_check', 'LIKE', '%' . $search . '%')
                      ->orWhere('production_batch.batch_no', 'LIKE', '%' . $search . '%')
                      ->orWhere('production.Approve_status', 'LIKE', '%' . $search . '%');
                } elseif ($queryType === 'mrn') {
                    $q->orWhere('mrn_stock_transfer_details.serial_no', 'LIKE', '%' . $search . '%')
                      ->orWhere('master_raw_material_details.Invoice_No', 'LIKE', '%' . $search . '%')
                      ->orWhere('mrn_stock_transfer.Approve_status', 'LIKE', '%' . $search . '%');
                } elseif ($queryType === 'fg') {
                    $q->orWhere('finished_good_gatepasses_details.serial_no', 'LIKE', '%' . $search . '%')
                      ->orWhere('finished_good_gatepasses.Approve_status', 'LIKE', '%' . $search . '%');
                }
            });
        }
    }

    /**
     * Get filter options with minimal memory usage - only unique values for dropdowns
     */
    private function getFilterOptions()
    {
        // Get ALL available serial numbers for dropdown filters (not limited by date range)
        // This ensures all serial numbers are available in dropdown even if not in current date range
        
        // Get unique values from production (without date restriction)
        $productionOptions = DB::table('production_batch')
            ->join('production', 'production_batch.productionID', '=', 'production.id')
            ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->whereNotNull('production_batch.serail_check')
            ->where('production_batch.serail_check', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'production_batch.serail_check')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            })
            ->select(
                'production_batch.serail_check as serial_no',
                'prj_material.material_name as matname',
                DB::raw("'Production' as source")
            )
            ->get();

        // Get unique values from MRN (without date restriction)
        $mrnOptions = DB::table('mrn_stock_transfer_details')
            ->join('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
            ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->whereNotNull('mrn_stock_transfer_details.serial_no')
            ->where('mrn_stock_transfer_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'mrn_stock_transfer_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            })
            ->select(
                'mrn_stock_transfer_details.serial_no',
                'prj_material.material_name as matname',
                DB::raw("'MRN Stock Transfer' as source")
            )
            ->get();

        // Get unique values from FG (without date restriction)
        $fgOptions = DB::table('finished_good_gatepasses_details')
            ->join('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
            ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->whereNotNull('finished_good_gatepasses_details.serial_no')
            ->where('finished_good_gatepasses_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'finished_good_gatepasses_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            })
            ->select(
                'finished_good_gatepasses_details.serial_no',
                'prj_material.material_name as matname',
                DB::raw("'Manual FG Entry' as source")
            )
            ->get();

        // Combine all options
        return $productionOptions->merge($mrnOptions)->merge($fgOptions);
    }

    /**
     * Build dispatched serial numbers query.
     */
    private function buildDispatchedSerialsQuery($fromdate, $dateto, $statusFilter = null, $materialIdFilter = null, $serialNumberFilter = null, $sourceFilter = null, $searchTerm = null)
    {
        $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
        $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');

        $query = DB::table('dd_crmwtp_dispatch_items_slno')
            ->leftJoin('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
            ->leftJoin('crmwtp_product_details', 'dd_crmwtp_dispatch_items_slno.material_id', '=', 'crmwtp_product_details.id')
            ->leftJoin('prj_material', 'crmwtp_product_details.matrl_id', '=', 'prj_material.id')
            ->leftJoin('fin_customers', 'dd_crmwtp_dispatch_details.customer', '=', 'fin_customers.id')
            ->select(
                'dd_crmwtp_dispatch_items_slno.serial_no',
                DB::raw("COALESCE(prj_material.material_name, crmwtp_product_details.model_name, 'N/A') as matname"),
                DB::raw("COALESCE(crmwtp_product_details.hsn, 'N/A') as HSN_Code"),
                DB::raw("COALESCE(crmwtp_product_details.uom, 'N/A') as UOM"),
                DB::raw("'N/A' as batch_no"),
                'dd_crmwtp_dispatch_details.date as invoice_date',
                'dd_crmwtp_dispatch_details.invoice_no',
                'dd_crmwtp_dispatch_details.invoice_date as actual_invoice_date',
                DB::raw("'N/A' as gatepass_no"),
                DB::raw("COALESCE(fin_customers.companynm, 'N/A') as party_name"),
                'dd_crmwtp_dispatch_details.dispatch_status as raw_status',
                'dd_crmwtp_dispatch_details.date as transaction_date',
                DB::raw("'Dispatch' as source"),
                'dd_crmwtp_dispatch_details.id as source_id',
                DB::raw("CASE 
                    WHEN dd_crmwtp_dispatch_details.dispatch_status IN (0,2) THEN 'PENDING'
                    WHEN dd_crmwtp_dispatch_details.dispatch_status = 1 THEN 'APPROVE'
                    WHEN dd_crmwtp_dispatch_details.dispatch_status = 3 THEN 'RECHECK'
                    WHEN dd_crmwtp_dispatch_details.dispatch_status = 4 THEN 'HOLD'
                    WHEN dd_crmwtp_dispatch_details.dispatch_status = 6 THEN 'REJECT'
                    ELSE 'PENDING'
                END as status")
            )
            ->whereDate('dd_crmwtp_dispatch_details.date', '>=', $fromdate)
            ->whereDate('dd_crmwtp_dispatch_details.date', '<=', $dateto)
            ->whereNotNull('dd_crmwtp_dispatch_items_slno.serial_no')
            ->where('dd_crmwtp_dispatch_items_slno.serial_no', '!=', '');

        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $query->whereIn('dd_crmwtp_dispatch_details.dispatch_status', [0, 2]);
            } elseif ($statusFilter === 'APPROVE') {
                $query->where('dd_crmwtp_dispatch_details.dispatch_status', 1);
            } elseif ($statusFilter === 'RECHECK') {
                $query->where('dd_crmwtp_dispatch_details.dispatch_status', 3);
            } elseif ($statusFilter === 'HOLD') {
                $query->where('dd_crmwtp_dispatch_details.dispatch_status', 4);
            } elseif ($statusFilter === 'REJECT') {
                $query->where('dd_crmwtp_dispatch_details.dispatch_status', 6);
            }
        }

        if ($materialIdFilter) {
            $query->where('crmwtp_product_details.id', $materialIdFilter);
        }

        if ($serialNumberFilter) {
            $query->where(DB::raw('LOWER(dd_crmwtp_dispatch_items_slno.serial_no)'), 'LIKE', '%' . strtolower(trim($serialNumberFilter)) . '%');
        }

        if ($sourceFilter) {
            $query->where('dd_crmwtp_dispatch_details.dispatch_status', 'LIKE', '%' . $sourceFilter . '%');
        }

        if ($searchTerm) {
            $search = '%' . strtolower(trim($searchTerm)) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(prj_material.material_name) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(crmwtp_product_details.model_name) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(crmwtp_product_details.hsn) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(crmwtp_product_details.uom) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(dd_crmwtp_dispatch_items_slno.serial_no) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(dd_crmwtp_dispatch_details.invoice_no) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(fin_customers.companynm) LIKE ?', [$search]);
            });
        }

        return $query;
    }

    private function getAllDispatchedSerialsPaginated($fromdate, $dateto, $statusFilter = null, $materialIdFilter = null, $serialNumberFilter = null, $sourceFilter = null, $searchTerm = null)
    {
        return $this->buildDispatchedSerialsQuery($fromdate, $dateto, $statusFilter, $materialIdFilter, $serialNumberFilter, $sourceFilter, $searchTerm)
            ->orderBy('transaction_date', 'DESC')
            ->paginate(10)
            ->appends(request()->query());
    }

    private function getAllDispatchedSerials($fromdate, $dateto, $statusFilter = null, $materialIdFilter = null, $serialNumberFilter = null, $sourceFilter = null, $searchTerm = null)
    {
        return $this->buildDispatchedSerialsQuery($fromdate, $dateto, $statusFilter, $materialIdFilter, $serialNumberFilter, $sourceFilter, $searchTerm)
            ->orderBy('transaction_date', 'DESC')
            ->get();
    }

    public function exportdata(Request $request)
    {
        // Get parameters from request or use defaults
        $fromdate = $request->input('from_date');
        $dateto = $request->input('to_date');

        if (empty($fromdate) && empty($dateto)) {
            $fromdate = \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
            $dateto = \Carbon\Carbon::now()->format('Y-m-d');
        }

        if (!empty($fromdate) && empty($dateto)) {
            $dateto = $fromdate;
        }

        if (empty($fromdate) && !empty($dateto)) {
            $fromdate = $dateto;
        }

        $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
        $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');

        // Get filters from request
        $statusFilter = $request->status;
        $materialNameFilter = $request->material_name;
        $searchTerm = $request->search; // Changed from serial_number to search
        $sourceFilter = $request->source;

        // Use the same query logic as listing page to ensure identical ordering
        // Build the exact same union query
        $productionQuery = DB::table('production_batch')
            ->join('production', 'production_batch.productionID', '=', 'production.id')
            ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'production_batch.serail_check as serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("COALESCE(production_batch.batch_no, 'N/A') as batch_no"),
                'production.Production_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(production.Approve_status, 'PENDING') as status"),
                'production.Production_Date as transaction_date',
                DB::raw("'Production' as source"),
                'production.id as source_id'
            )
            ->whereNotNull('production_batch.serail_check')
            ->where('production_batch.serail_check', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'production_batch.serail_check')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        $mrnQuery = DB::table('mrn_stock_transfer_details')
            ->join('mrn_stock_transfer', 'mrn_stock_transfer_details.mrn_st_id', '=', 'mrn_stock_transfer.id')
            ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
            ->select(
                'mrn_stock_transfer_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'mrn_stock_transfer.purchahedate as invoice_date',
                DB::raw("master_raw_material_details.Invoice_No as invoice_no"),
                DB::raw("COALESCE(mrn_stock_transfer.Approve_status, 'PENDING') as status"),
                'mrn_stock_transfer.purchahedate as transaction_date',
                DB::raw("'MRN Stock Transfer' as source"),
                'mrn_stock_transfer.id as source_id'
            )
            ->whereNotNull('mrn_stock_transfer_details.serial_no')
            ->where('mrn_stock_transfer_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'mrn_stock_transfer_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        $fgQuery = DB::table('finished_good_gatepasses_details')
            ->join('finished_good_gatepasses', 'finished_good_gatepasses_details.fg_id', '=', 'finished_good_gatepasses.id')
            ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->select(
                'finished_good_gatepasses_details.serial_no',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM',
                DB::raw("'N/A' as batch_no"),
                'finished_good_gatepasses.Transaction_Date as invoice_date',
                DB::raw("'N/A' as invoice_no"),
                DB::raw("COALESCE(finished_good_gatepasses.Approve_status, 'PENDING') as status"),
                'finished_good_gatepasses.Transaction_Date as transaction_date',
                DB::raw("'Manual FG Entry' as source"),
                'finished_good_gatepasses.id as source_id'
            )
            ->whereNotNull('finished_good_gatepasses_details.serial_no')
            ->where('finished_good_gatepasses_details.serial_no', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dd_crmwtp_dispatch_items_slno')
                    ->join('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items_slno.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                    ->whereColumn('dd_crmwtp_dispatch_items_slno.serial_no', 'finished_good_gatepasses_details.serial_no')
                    ->where('dd_crmwtp_dispatch_details.dispatch_status', '!=', '6');
            });

        // Apply date filters
        if (!empty($fromdate) && !empty($dateto)) {
            $productionQuery->whereBetween('production.Production_Date', [$fromdate, $dateto]);
            $mrnQuery->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $dateto]);
            $fgQuery->whereBetween('finished_good_gatepasses.Transaction_Date', [$fromdate, $dateto]);
        }

        // Apply filters using the same method as listing
        $this->applyFiltersToQuery($productionQuery, $statusFilter, $materialNameFilter, null, $sourceFilter, $searchTerm, 'production');
        $this->applyFiltersToQuery($mrnQuery, $statusFilter, $materialNameFilter, null, $sourceFilter, $searchTerm, 'mrn');
        $this->applyFiltersToQuery($fgQuery, $statusFilter, $materialNameFilter, null, $sourceFilter, $searchTerm, 'fg');

        // Create union query
        $unionQuery = $productionQuery
            ->union($mrnQuery)
            ->union($fgQuery);

        // Get all records with same ordering as listing (use get() instead of paginate())
        $availableSerials = DB::table(DB::raw("({$unionQuery->toSql()}) as combined_results"))
            ->mergeBindings($unionQuery)
            ->orderBy('transaction_date', 'DESC')
            ->get();

        // Get checkbox preferences for this user and table
        $Checkbox = \App\Models\CheckBox::where('userID', auth()->user()->id)->where('tableID', 97)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        $serialNumber = 1;
        foreach ($availableSerials as $val) {
            $rowData = [
                'SL No' => $serialNumber,
                'Material Name' => $val->matname ?? 'N/A',
                'Source' => $val->source ?? 'N/A',
                'Serial No' => $val->serial_no ?? 'N/A',
                'HSN' => $val->HSN_Code ?? 'N/A',
                'UOM' => $val->UOM ?? 'N/A',
                'Batch No' => $val->batch_no ?? 'N/A',
                'Invoice Date / Manufacturing Date' => $val->invoice_date ? \Carbon\Carbon::parse($val->invoice_date)->format('d-m-Y') : 'N/A',
                'Invoice No' => $val->invoice_no ?? 'N/A',
                'Status' => $val->status ?? 'PENDING'
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $column => $value) {
                    if (in_array($column, $Checkbox_Arr)) {
                        $filteredRow[$column] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
            $serialNumber++;
        }

        $file = "Serial Number Available data.csv";
        $this->collectionExport($d, $file);
    }

    public function exportdispatchdata(Request $request)
    {
        $fromdate = $request->input('from_date');
        $dateto = $request->input('to_date');
        $searchTerm = $request->input('search', '');
        $statusFilter = $request->status;
        $sourceFilter = $request->source;

        if (empty($fromdate) && empty($dateto)) {
            $fromdate = \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
            $dateto = \Carbon\Carbon::now()->format('Y-m-d');
        }

        if (!empty($fromdate) && empty($dateto)) {
            $dateto = $fromdate;
        }

        if (empty($fromdate) && !empty($dateto)) {
            $fromdate = $dateto;
        }

        $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
        $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');

        $materialIdFilter = $request->input('material_id');

        // Get all dispatched serial numbers for export with current filters
        $dispatchedSerials = $this->getAllDispatchedSerials($fromdate, $dateto, $statusFilter, $materialIdFilter, null, $sourceFilter, $searchTerm);

        // Get checkbox preferences for this user and table (using different tableID for dispatch report)
        $Checkbox = \App\Models\CheckBox::where('userID', auth()->user()->id)->where('tableID', 98)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        $serialNumber = 1;
        foreach ($dispatchedSerials as $val) {
            $rowData = [
                'SL No' => $serialNumber,
                'Material Name' => $val->matname ?? 'N/A',
                'Serial No' => $val->serial_no ?? 'N/A',
                'HSN' => $val->HSN_Code ?? 'N/A',
                'UOM' => $val->UOM ?? 'N/A',
                'Dispatch Date' => $val->invoice_date ? \Carbon\Carbon::parse($val->invoice_date)->format('d-m-Y') : 'N/A',
                'Invoice No' => $val->invoice_no ?? 'N/A',
                'Invoice Date' => $val->actual_invoice_date ? \Carbon\Carbon::parse($val->actual_invoice_date)->format('d-m-Y') : 'N/A',
                'Gatepass No' => $val->gatepass_no ?? 'N/A',
                'Party Name' => $val->party_name ?? 'N/A',
                'Status' => $val->status ?? 'PENDING'
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $column => $value) {
                    if (in_array($column, $Checkbox_Arr)) {
                        $filteredRow[$column] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
            $serialNumber++;
        }

        $file = "dispatched_serial_number_report_data.csv";
        $this->collectionExport($d, $file);
    }

    public function collectionExport($d, $file)
    {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $file);
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        $fp = fopen('php://output', 'w');
        
        // Check if data exists
        if (count($d) > 0) {
            // Write header row first
            fputcsv($fp, array_keys($d[0]));
            
            // Write data rows
            foreach ($d as $row) {
                fputcsv($fp, array_values($row));
            }
        } else {
            // Write a message if no data
            fputcsv($fp, ['No data found for the selected criteria']);
        }
        
        fclose($fp);
        exit();
    }
     /**
     * Get paginated WIP serial numbers (factory serial numbers with status NULL in details table)
     */
    private function getWipSerialsPaginated($fromdate, $dateto, $statusFilter = null, $organizationFilter = null, $shiftNameFilter = null, $shiftNameNmFilter = null, $searchTerm = null)
    {
        // Build query for factory serial numbers where detail status is NULL
        $query = DB::table('factory_serial_number_details as fsnd')
            ->join('factory_serial_numbers as fsn', 'fsnd.sl_id', '=', 'fsn.id')
            ->join('hr_mstr_shift as hms', 'fsn.Shift_Name', '=', 'hms.shift_code')
            ->select(
                'fsnd.sl_no',
                'fsn.Organization_Name',
                'fsn.Shift_Name',
                'fsn.fg_watt',
                'fsn.bus_bar',
                'fsn.serial_date',
                'hms.shift as Shift_Name_nm',
                DB::raw("COALESCE(fsn.Approve_status, 'PENDING') as Approve_status")
            )
            ->whereNull('fsnd.status'); // Only show records where status is NULL (unused)

            

        // Always apply date range filter
        if (!empty($fromdate) && !empty($dateto)) {
            $query->whereBetween('fsn.serial_date', [$fromdate, $dateto]);
        }

        // Apply status filter
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $query->where(function($q) {
                    $q->whereNull('fsn.Approve_status')
                      ->orWhere('fsn.Approve_status', '')
                      ->orWhere('fsn.Approve_status', 'PENDING');
                });
            } else {
                $query->where('fsn.Approve_status', $statusFilter);
            }
        }

        // Apply organization filter
        if ($organizationFilter) {
            $query->where('fsn.Organization_Name', 'LIKE', '%' . $organizationFilter . '%');
        }

        // Apply shift name filter
        if ($shiftNameFilter) {
            $query->where('fsn.Shift_Name', 'LIKE', '%' . $shiftNameFilter . '%');
        }

        // Apply shift name nm filter
        if ($shiftNameNmFilter) {
            $query->where('hms.shift', 'LIKE', '%' . $shiftNameNmFilter . '%');
        }

        // Apply search term filter (searches across multiple fields)
        if (!empty($searchTerm)) {
            $searchTerm = trim($searchTerm);
            $query->where(function($q) use ($searchTerm) {
                $q->where(DB::raw('LOWER(fsnd.sl_no)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.Organization_Name)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.Shift_Name)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(hms.shift)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.fg_watt)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.bus_bar)'), 'LIKE', '%' . strtolower($searchTerm) . '%');
            });
        }

        // Order by serial date descending and paginate
        return $query->orderBy('fsn.serial_date', 'desc')
                     ->orderBy('fsnd.sl_no', 'asc')
                     ->paginate(10)
                     ->appends(request()->query());
    }

    /**
     * Get lightweight filter options for WIP report
     */
    private function getWipFilterOptions()
    {
        return DB::table('factory_serial_number_details as fsnd')
            ->join('factory_serial_numbers as fsn', 'fsnd.sl_id', '=', 'fsn.id')
            ->join('hr_mstr_shift as hms', 'fsn.Shift_Name', '=', 'hms.shift_code')
            ->select(
                'fsn.Organization_Name',
                'fsn.Shift_Name',
                'hms.shift as Shift_Name_nm',
                'fsn.Approve_status'
            )
            ->whereNull('fsnd.status')
            ->distinct()
            ->get();
    }
    
    /**
     * Export WIP serial numbers data
     */
    public function exportdata_wip_serial(Request $request)
    {
        // Get parameters from request or use defaults
        $fromdate = $request->input('from_date');
        $dateto = $request->input('to_date');

        if (empty($fromdate) && empty($dateto)) {
            $fromdate = null;
            $dateto = null;
        }

        if (!empty($fromdate) && empty($dateto)) {
            $dateto = $fromdate;
        }

        if (empty($fromdate) && !empty($dateto)) {
            $fromdate = $dateto;
        }

        // Format dates consistently if they exist
        if ($fromdate) {
            $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
        }
        if ($dateto) {
            $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');
        }

        // Get filters from request
        $statusFilter = $request->status;
        $organizationFilter = $request->organization_name;
        $shiftNameFilter = $request->shift_name;
        $shiftNameNmFilter = $request->shift_name_nm;
        $searchTerm = $request->input('search', '');

        // Get WIP serial numbers without pagination for export
        $wipSerials = DB::table('factory_serial_number_details as fsnd')
            ->join('factory_serial_numbers as fsn', 'fsnd.sl_id', '=', 'fsn.id')
            ->join('hr_mstr_shift as hms', 'fsn.Shift_Name', '=', 'hms.shift_code')
            ->select(
                'fsnd.sl_no',
                'fsn.Organization_Name',
                'fsn.Shift_Name',
                'hms.shift as Shift_Name_nm',
                'fsn.fg_watt',
                'fsn.bus_bar',
                'fsn.serial_date',
                DB::raw("COALESCE(fsn.Approve_status, 'PENDING') as Approve_status")
            )
            ->whereNull('fsnd.status');

        // Apply date range filter
        if (!empty($fromdate) && !empty($dateto)) {
            $wipSerials->whereBetween('fsn.serial_date', [$fromdate, $dateto]);
        }

        // Apply filters
        if ($statusFilter) {
            if ($statusFilter === 'PENDING') {
                $wipSerials->where(function($q) {
                    $q->whereNull('fsn.Approve_status')
                      ->orWhere('fsn.Approve_status', '')
                      ->orWhere('fsn.Approve_status', 'PENDING');
                });
            } else {
                $wipSerials->where('fsn.Approve_status', $statusFilter);
            }
        }

        if ($organizationFilter) {
            $wipSerials->where('fsn.Organization_Name', 'LIKE', '%' . $organizationFilter . '%');
        }

        if ($shiftNameFilter) {
            $wipSerials->where('fsn.Shift_Name', 'LIKE', '%' . $shiftNameFilter . '%');
        }

        if ($shiftNameNmFilter) {
            $wipSerials->where('hms.shift', 'LIKE', '%' . $shiftNameNmFilter . '%');
        }

        if (!empty($searchTerm)) {
            $searchTerm = trim($searchTerm);
            $wipSerials->where(function($q) use ($searchTerm) {
                $q->where(DB::raw('LOWER(fsnd.sl_no)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.Organization_Name)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(fsn.Shift_Name)'), 'LIKE', '%' . strtolower($searchTerm) . '%')
                  ->orWhere(DB::raw('LOWER(hms.shift)'), 'LIKE', '%' . strtolower($searchTerm) . '%');
            });
        }

        $wipSerials = $wipSerials->orderBy('fsn.serial_date', 'desc')
                                 ->orderBy('fsnd.sl_no', 'asc')
                                 ->get();

        // Get checkbox preferences for this user and table
        $Checkbox = \App\Models\CheckBox::where('userID', auth()->user()->id)->where('tableID', 98)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        $serialNumber = 1;
        foreach ($wipSerials as $val) {
            $rowData = [
                'SL No' => $serialNumber,
                'Organization Name' => $val->Organization_Name ?? 'N/A',
                'Serial No' => $val->sl_no ?? 'N/A',
                'Shift Code' => $val->Shift_Name ?? 'N/A',
                'Shift Name' => $val->Shift_Name_nm ?? 'N/A',
                'FG Watt' => $val->fg_watt ?? 'N/A',
                'Bus Bar' => $val->bus_bar ?? 'N/A',
                'Serial Date' => $val->serial_date ? \Carbon\Carbon::parse($val->serial_date)->format('d-m-Y') : 'N/A',
                'Approval Status' => $val->Approve_status ?? 'PENDING'
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $column => $value) {
                    if (in_array($column, $Checkbox_Arr)) {
                        $filteredRow[$column] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
            $serialNumber++;
        }

        $file = "WIP_Serial_Numbers_Report.csv";
        $this->collectionExport($d, $file);
    }

}
