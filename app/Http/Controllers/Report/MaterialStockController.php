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
use App\Models\ProductionProcess\{Production_Process, Production_Process_Machine, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom, prj_organisation, Factory_Address_Detail};
use App\Models\Master\{Master_Plant_Machinery, Module_Bsns_Unit, Prj_Inventory, Pur_Address};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Production\{Production_For_Sales, Production_For_Stock, ProductionData, ProductionBatch, Production, ProductionApprove};
use App\Models\Storeissue\{Store_issue, StoreIssueApprove, StoreIssueApprovedMaterial};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer, Mrn_Stock_Transfer_Detail, Mrn_Stock_Transfer_Approve};
use App\Models\FinishedGood\FinishedGoodGatepass;

class MaterialStockController extends Controller
{

    public function list(Request $request)
    {
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

        $query = PlantStock::where('type', 1);
        $plant_stock_DATA = $query->get();

        $Material_arr = [];
        $Material_Name_arr = [];
        $lpp_arr = [];
        $prj_project = prj_project::select('prj_project.*')
            ->leftJoin('factory_address_details', 'factory_address_details.name_of_unit', '=', 'prj_project.id')
            ->where('factory_address_details.Approve_status', 'APPROVE')
            ->groupBy('factory_address_details.name_of_unit')
            ->get();
        // dd($plant_stock_DATA);
        $Organization = prj_organisation::all();
        $Prj_Subproject = Prj_Subproject::all();
        $Godown_Name = Prj_Inventory::all();

        foreach ($plant_stock_DATA as $val) {
            //  $totalmrntransferqty=Mrn_Stock_Transfer::select('')
            //                 ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
            //                 ->where('Prj_Id',$request->Manufacturing_Unit);
            $total_received_qtyQuery = $total_FinishedGood_received_qtyQuery = '';
            $total_received_qtyQuery = Production::query();
            $total_FinishedGood_received_qtyQuery = FinishedGoodGatepass::query();
            $totalmrntransferqty = Mrn_Stock_Transfer::select('master_raw_material_details.Prj_Id', 'master_raw_material_details.Subprj_Id', 'master_raw_material_details.Organization', 'mrn_stock_transfer.*')
                ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id');

            if ($request->has('Manufacturing_Unit') && !empty($request->Manufacturing_Unit)) {
                $total_received_qtyQuery = Production::where('Unit_Name', $request->Manufacturing_Unit);
                $total_FinishedGood_received_qtyQuery = FinishedGoodGatepass::where('Unit_Name', $request->Manufacturing_Unit);
                $totalmrntransferqty->where('master_raw_material_details.Prj_Id', $request->Manufacturing_Unit);
            }

            if ($request->has('Plant_Name') && !empty($request->Plant_Name)) {
                $total_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
                $total_FinishedGood_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
                $totalmrntransferqty->where('master_raw_material_details.Subprj_Id', $request->Plant_Name);
            }

            // Apply Organization filter for Production and FinishedGoodGatepass
            if ($request->has('Organization') && !empty($request->Organization)) {
                $total_received_qtyQuery->leftJoin('prj_organisation as p1', 'production.Organization_Name', '=', 'p1.id')
                    ->where('p1.id', $request->Organization);

                $total_FinishedGood_received_qtyQuery->leftJoin('prj_organisation as p2', 'finished_good_gatepasses.Organization_Name', '=', 'p2.id')
                    ->where('p2.id', $request->Organization);
                $totalmrntransferqty->where('master_raw_material_details.Organization', $request->Organization);
            }
            if ($request->has('Godown_Name') && !empty($request->Godown_Name)) {
                $total_FinishedGood_received_qtyQuery->where('Godown_Name', $request->Godown_Name);
                $totalmrntransferqty->where('mrn_stock_transfer.Godown_Name', $request->Godown_Name);
            }

            $total_received_qtyQuery->where('Raw_Material', $val->materialID);
            $total_FinishedGood_received_qtyQuery->where('Material_id', $val->materialID);
            $totalmrntransferqty->where('mrn_stock_transfer.Material_id', $val->materialID);
            // dd($total_received_qtyQuery->toSql(),
            //   $total_received_qtyQuery->getBindings());
            //return $total_issue_qtyQuery = Dd_crmwtp_dispatch_item::all(); 

            //dd($total_issue_qtyQuery->get());                  

            //$total_issue_qtyQuery = OutGatepassItemDetails::where('out_gatepass_item_details.item_desc', $val->materialID);

            // Issue quantity query with organization filter
            $total_issue_qtyQuery = Dd_crmwtp_dispatch_item::select(
                'dd_crmwtp_dispatch_items.material_id',
                'materialmanagement_add_material.id as matid',
                'dd_crmwtp_dispatch_items.dispatch_qty',
                'dd_crmwtp_dispatch_details.created_at',
                'dd_crmwtp_dispatch_details.dispatch_status',
                'prj_organisation.organisation'
            )
                ->leftJoin('crmwtp_product_details', 'dd_crmwtp_dispatch_items.material_id', '=', 'crmwtp_product_details.id')
                ->leftJoin('prj_material', 'crmwtp_product_details.matrl_id', '=', 'prj_material.id')
                ->leftJoin('materialmanagement_add_material', 'prj_material.id', '=', 'materialmanagement_add_material.Material_Name')
                ->leftJoin('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                ->leftJoin('dd_crmwtp_po_details', 'dd_crmwtp_dispatch_details.so_no', '=', 'dd_crmwtp_po_details.ref_id')
                ->leftJoin('prj_organisation', 'dd_crmwtp_po_details.organisation', '=', 'prj_organisation.id')
                ->where('dd_crmwtp_dispatch_details.dispatch_status', '=', '1')
                ->where('materialmanagement_add_material.id', $val->materialID);

            if ($request->has('Organization') && !empty($request->Organization)) {
                $total_issue_qtyQuery->where('prj_organisation.id', $request->Organization);
            }
            $total_issue_qtyQuery->where('materialmanagement_add_material.id', $val->materialID);

            $materialData = MaterialManagement_Add_Material::select(
                'materialmanagement_add_material.*',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM'
            )
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->where('materialmanagement_add_material.id', $val->materialID)
                ->first();

            $lppamountQuery = Master_Raw_Material::where('Material', $val->materialID);
            $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
            $total_received_qty_before =
                (clone $total_received_qtyQuery)
                ->where('Production_Date', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $total_FinishedGood_received_qtyQuery)
                ->where('Transaction_Date', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $totalmrntransferqty)
                ->where('purchahedate', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('purchase_qty');


            $total_issue_qty_before = (clone $total_issue_qtyQuery)->where('dd_crmwtp_dispatch_details.date', '<', $fromdate)->sum('dispatch_qty');

            $opening_qty = $total_received_qty_before - $total_issue_qty_before;

            $total_received_qty = (clone $total_received_qtyQuery)
                ->whereBetween('Production_Date', [$fromdate, $dateto])
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $total_FinishedGood_received_qtyQuery)
                ->whereBetween('Transaction_Date', [$fromdate, $dateto])
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $totalmrntransferqty)
                ->whereBetween('purchahedate', [$fromdate, $dateto])
                ->where('Approve_status', 'APPROVE')
                ->sum('purchase_qty');

            if ($request->has('Manufacturing_Unit') && !empty($request->Manufacturing_Unit)) {
                $total_issue_qty = 0;
            } else {
                $total_issue_qty = (clone $total_issue_qtyQuery)
                    ->whereBetween('dd_crmwtp_dispatch_details.date', [$fromdate, $dateto])
                    ->sum('dispatch_qty');
            }
            $closing_qty = $opening_qty + $total_received_qty - $total_issue_qty;

            $lpp_arr[$val->materialID] = $lppamount ? $lppamount->Rate : "0";
            $Material_Name_arr[$val->materialID] = $materialData ? $materialData->matname : 'N/A';

            $Material_arr[$val->materialID] = [
                'data' => $val,
                'HSN_Code' => $materialData ? $materialData->HSN_Code : 'N/A',
                'UOM' => $materialData ? $materialData->UOM : 'N/A',
                'opening_qty' => $opening_qty,
                'total_received_qty' => $total_received_qty,
                'total_issue_qty' => $total_issue_qty,
                'closing_qty' => $closing_qty
            ];
        }
        return view('Report/MaterialStockList', [
            'Materials' => $Material_arr,
            'lpp_arr' => $lpp_arr,
            'fromdate' => $fromdate,
            'todate' => $dateto,
            'Material_Name' => $Material_Name_arr,
            'Manufacturing_unit' => $prj_project,
            'plant_name' => $Prj_Subproject,
            'Organization' => $Organization,
            'Godown_Name' => $Godown_Name
        ]);
    }

    public function view(Request $request, $id)
    {
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

        $Receivedmaterials = [];
        $Issuedmaterials = [];
        $FinishedGoodmaterials = [];
        $MRNTransfermaterials = [];

        $query = Production::select(
            'production.*',
            'prj_material.material_name as matname',
            'prj_organisation.organisation as fromorg',
            'prj_project.pname',
            'prj_subproject.spname',
            'materialmanagement_add_material.HSN_Code'
        )
            ->leftJoin('materialmanagement_add_material', 'production.Raw_Material', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_project', 'production.Unit_Name', '=', 'prj_project.id')
            ->leftJoin('prj_subproject', 'production.Plant_Name', '=', 'prj_subproject.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('prj_organisation', 'production.Organization_Name', '=', 'prj_organisation.id')
            ->where('production.Raw_Material', $id)
            ->where('production.Approve_status', 'APPROVE')
            ->whereBetween('production.Production_Date', [$fromdate, $dateto]);

         $query2 = Dd_crmwtp_dispatch_item::select(
            'dd_crmwtp_dispatch_items.material_id',
            'materialmanagement_add_material.id as matid',
            'dd_crmwtp_dispatch_items.dispatch_qty',
            'dd_crmwtp_dispatch_details.created_at as transact_dt',
            'dd_crmwtp_dispatch_details.dispatch_status',
            'prj_material.material_name as matname',
            'crmwtp_product_details.uom',
            'crmwtp_product_details.hsn',
            'prj_organisation.organisation as toorg',
            'fin_customers.companynm as customer_name'
        )
            ->leftJoin('crmwtp_product_details', 'dd_crmwtp_dispatch_items.material_id', '=', 'crmwtp_product_details.id')
            ->leftJoin('prj_material', 'crmwtp_product_details.matrl_id', '=', 'prj_material.id')
            ->leftJoin('materialmanagement_add_material', 'prj_material.id', '=', 'materialmanagement_add_material.Material_Name')
            ->leftJoin('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
            ->leftJoin('dd_crmwtp_po_details', 'dd_crmwtp_dispatch_details.so_no', '=', 'dd_crmwtp_po_details.ref_id')
            ->leftJoin('prj_organisation', 'dd_crmwtp_po_details.organisation', '=', 'prj_organisation.id')
            ->leftJoin('fin_customers', 'dd_crmwtp_dispatch_details.customer', '=', 'fin_customers.id')
            ->where('dd_crmwtp_dispatch_details.dispatch_status', '=', '1')
            ->where('materialmanagement_add_material.id', $id)
            ->whereBetween('dd_crmwtp_dispatch_details.date', [$fromdate, $dateto]);

         $query3 = FinishedGoodGatepass::select(
            'finished_good_gatepasses.*',
            'prj_material.material_name as matname',
            'prj_organisation.organisation as fromorg',
            'prj_project.pname',
            'prj_subproject.spname',
            'prj_inventory.inventory_name as fromgodown'
        )
            ->leftJoin('materialmanagement_add_material', 'finished_good_gatepasses.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_organisation', 'finished_good_gatepasses.Organization_Name', '=', 'prj_organisation.id')
            ->leftJoin('prj_project', 'finished_good_gatepasses.Unit_Name', '=', 'prj_project.id')
            ->leftJoin('prj_subproject', 'finished_good_gatepasses.Plant_Name', '=', 'prj_subproject.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('prj_inventory', 'finished_good_gatepasses.Godown_Name', '=', 'prj_inventory.id')
            ->where('finished_good_gatepasses.Approve_status', 'APPROVE')
            ->where('finished_good_gatepasses.Material_id', $id)
            ->whereBetween('finished_good_gatepasses.Transaction_Date', [$fromdate, $dateto]);

        $MRNTransferQuery = Mrn_Stock_Transfer::select(
            'mrn_stock_transfer.*',
            'master_raw_material_details.Prj_Id',
            'master_raw_material_details.Subprj_Id',
            'master_raw_material_details.Organization',
            'prj_material.material_name as matname',
            'materialmanagement_add_material.HSN_Code',
            'prj_organisation.organisation as fromorg',
            'prj_inventory.inventory_name as fromgodown'
        )
            ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
            ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->leftJoin('prj_organisation', 'mrn_stock_transfer.Organization_Name', '=', 'prj_organisation.id')
            ->leftJoin('prj_inventory', 'mrn_stock_transfer.Godown_Name', '=', 'prj_inventory.id')
            ->where('mrn_stock_transfer.Material_id', $id)
            ->where('mrn_stock_transfer.Approve_status', 'APPROVE')
            ->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $dateto]);

        // Fetch all data
        $Receivedmaterials = $query->get();
        $Issuedmaterials = $query2->get();
        $FinishedGoodmaterials = $query3->get();
        $MRNTransfermaterials = $MRNTransferQuery->get();

        // Combine all material flows
        $allMaterials = collect([]);

        foreach ($Receivedmaterials as $item) {
            $item->HSN_Code = $item->HSN_Code ?? 'N/A';
            $item->transaction_date = \Carbon\Carbon::parse($item->Production_Date)->toDateString();
            $item->type = 'Received';
            $allMaterials->push($item);
        }

        foreach ($Issuedmaterials as $item) {
            $item->transaction_date = \Carbon\Carbon::parse($item->transact_dt)->toDateString();
            $item->type = 'Issued';
            $allMaterials->push($item);
        }

        foreach ($FinishedGoodmaterials as $item) {
            $item->transaction_date = \Carbon\Carbon::parse($item->Transaction_Date)->toDateString();
            $item->type = 'Finished Good';
            $allMaterials->push($item);
        }

        foreach ($MRNTransfermaterials as $item) {
            $item->transaction_date = \Carbon\Carbon::parse($item->purchahedate)->toDateString();
            $item->type = 'MRN Transfer';
            $allMaterials->push($item);
        }

        $Materials = $allMaterials->sortBy(function ($material) {
            return \Carbon\Carbon::parse($material->transaction_date);
        });

        $gatepass_no = $gatepass_id = '';
        foreach ($Issuedmaterials as $item) {
            $gatepass_no = $item->request_no ?? '';
            $gatepass_id = $item->in_gatepass_id ?? '';
        }

        return view('Report/MaterialStockDetail', [
            'Materials' => $Materials,
            'Receivedmaterials' => $Receivedmaterials,
            'Issuedmaterials' => $Issuedmaterials,
            'FinishedGoodmaterials' => $FinishedGoodmaterials,
            'MRNTransfermaterials' => $MRNTransfermaterials,
            'fromdate' => $fromdate,
            'todate' => $dateto,
            'matid' => $id,
            'gatepass_no' => $gatepass_no,
            'gatepass_id' => $gatepass_id
        ]);
    }


    // public function ExportMaterialStock(Request $request)
    // {
    //     $fromdate = $request->input('from_date');
    //     $dateto = $request->input('to_date');

    //     if (empty($fromdate) && empty($dateto)) {
    //         $fromdate = \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
    //         $dateto = \Carbon\Carbon::now()->format('Y-m-d');
    //     }

    //     if (!empty($fromdate) && empty($dateto)) {
    //         $dateto = $fromdate;
    //     }

    //     if (empty($fromdate) && !empty($dateto)) {
    //         $fromdate = $dateto;
    //     }

    //     $fromdate = \Carbon\Carbon::parse($fromdate)->format('Y-m-d');
    //     $dateto = \Carbon\Carbon::parse($dateto)->format('Y-m-d');

    //     $plant_stock_DATA = PlantStock::where('type', 1)->get();

    //     $exportData = [];

    //     foreach ($plant_stock_DATA as $key => $val) {
    //         $materialData = MaterialManagement_Add_Material::select(
    //             'materialmanagement_add_material.*',
    //             'prj_material.material_name as matname',
    //             'materialmanagement_add_material.HSN_Code',
    //             'materialmanagement_add_material.UOM'
    //         )
    //         ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
    //         ->where('materialmanagement_add_material.id', $val->materialID)
    //         ->first();

    //         $lppamount = Master_Raw_Material::where('Material', $val->materialID)
    //                     ->orderBy('id', 'DESC')
    //                     ->first();

    //         $total_received_qtyQuery = Production::where('Raw_Material', $val->materialID);
    //         $total_FinishedGood_received_qtyQuery = FinishedGoodGatepass::where('Material_id', $val->materialID);
    //         // $total_issue_qtyQuery = OutGatepassItemDetails::where('out_gatepass_item_details.item_desc', $val->materialID);
    //         $total_issue_qtyQuery = Dd_crmwtp_dispatch_item::select('dd_crmwtp_dispatch_items.material_id','materialmanagement_add_material.id as matid','dd_crmwtp_dispatch_items.dispatch_qty','dd_crmwtp_dispatch_details.created_at','dd_crmwtp_dispatch_details.dispatch_status')
    //             ->leftjoin('crmwtp_product_details','dd_crmwtp_dispatch_items.material_id','=','crmwtp_product_details.id')
    //             ->leftjoin('prj_material','crmwtp_product_details.matrl_id','=','prj_material.id')
    //             ->leftjoin('materialmanagement_add_material','prj_material.id','=','materialmanagement_add_material.Material_Name')
    //             ->leftjoin('dd_crmwtp_dispatch_details','dd_crmwtp_dispatch_items.dispatch_id','=','dd_crmwtp_dispatch_details.id')
    //             ->leftjoin('dd_crmwtp_po_details','dd_crmwtp_dispatch_details.so_no','=','dd_crmwtp_po_details.ref_id')
    //             ->leftjoin('prj_organisation','dd_crmwtp_po_details.organisation','=','prj_organisation.id')
    //             ->where('dd_crmwtp_dispatch_details.dispatch_status','=','1')
    //             ->where('materialmanagement_add_material.id', $val->materialID);

    //         if ($request->has('Manufacturing_Unit') && !empty($request->Manufacturing_Unit)) {
    //             $total_received_qtyQuery->where('Unit_Name', $request->Manufacturing_Unit);
    //             $total_FinishedGood_received_qtyQuery->where('Unit_Name', $request->Manufacturing_Unit);
    //         }

    //         if ($request->has('Plant_Name') && !empty($request->Plant_Name)) {
    //             $total_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
    //             $total_FinishedGood_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
    //         }

    //         // Apply Organization filter for Production and FinishedGoodGatepass
    //         if ($request->has('Organization') && !empty($request->Organization)) {
    //             $total_received_qtyQuery->leftJoin('prj_organisation as p1', 'production.Organization_Name', '=', 'p1.id')
    //                                     ->where('p1.id', $request->Organization);

    //             $total_FinishedGood_received_qtyQuery->leftJoin('prj_organisation as p2', 'finished_good_gatepasses.Organization_Name', '=', 'p2.id')
    //                                                 ->where('p2.id', $request->Organization);
    //         }

    //         $total_received_qty_before = (clone $total_received_qtyQuery)->where('Production_Date', '<', $fromdate)
    //             ->where('Approve_status', 'APPROVE')->sum('Quantity') 
    //             + (clone $total_FinishedGood_received_qtyQuery)
    //             ->where('Transaction_Date', '<', $fromdate)
    //             ->where('Approve_status', 'APPROVE')->sum('Quantity');

    //         $total_issue_qty_before = (clone $total_issue_qtyQuery)
    //             ->where('dd_crmwtp_dispatch_details.created_at', '<', $fromdate)
    //             ->sum('dispatch_qty');

    //         $opening_qty = $total_received_qty_before - $total_issue_qty_before;

    //         $total_received_qty = (clone $total_received_qtyQuery)
    //             ->whereBetween('Production_Date', [$fromdate, $dateto])
    //             ->where('Approve_status', 'APPROVE')
    //             ->sum('Quantity') 
    //             + (clone $total_FinishedGood_received_qtyQuery)
    //             ->whereBetween('Transaction_Date', [$fromdate, $dateto])
    //             ->where('Approve_status', 'APPROVE')
    //             ->sum('Quantity');

    //         // Apply Organization filter for Dispatch
    //         if ($request->has('Organization') && !empty($request->Organization)) {
    //             $total_issue_qtyQuery->where('prj_organisation.id', $request->Organization);
    //         }

    //         $total_issue_qty = (clone $total_issue_qtyQuery)
    //             ->whereBetween('dd_crmwtp_dispatch_details.created_at', [$fromdate, $dateto])
    //             ->sum('dispatch_qty');

    //         $closing_qty = $opening_qty + $total_received_qty - $total_issue_qty;

    //         $exportData[] = [
    //             "SL. No." => $key + 1,
    //             "MATERIAL NAME" => $materialData->matname ?? 'N/A',
    //             "HSN" => $materialData->HSN_Code ?? 'N/A',
    //             "UOM" => $materialData->UOM ?? 'N/A',
    //             "LPP" => $lppamount->Rate ?? 'N/A',
    //             "OPENING BALANCE" => $opening_qty,
    //             "TOTAL RECEIVED" => $total_received_qty,
    //             "TOTAL ISSUED" => $total_issue_qty,
    //             "CLOSING BALANCE" => $closing_qty,
    //         ];
    //     }

    //     $file = "Material_Stock_Data.csv";
    //     return $this->collectionExport($exportData, $file);
    // }
    public function ExportMaterialStock(Request $request)
    {
        
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

        $plant_stock_DATA = PlantStock::where('type', 1)->get();
        $exportData = [];
        $processedMaterials = []; // Track processed materials to avoid duplicates

        foreach ($plant_stock_DATA as $key => $val) {
            // Skip if this material has already been processed
            if (in_array($val->materialID, $processedMaterials)) {
                continue;
            }

            // Add to processed materials
            $processedMaterials[] = $val->materialID;

            $materialData = MaterialManagement_Add_Material::select(
                'materialmanagement_add_material.*',
                'prj_material.material_name as matname',
                'materialmanagement_add_material.HSN_Code',
                'materialmanagement_add_material.UOM'
            )
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->where('materialmanagement_add_material.id', $val->materialID)
                ->first();

            $lppamount = Master_Raw_Material::where('Material', $val->materialID)
                ->orderBy('id', 'DESC')
                ->first();

            // Received queries
            $total_received_qtyQuery = Production::query();
            $total_FinishedGood_received_qtyQuery = FinishedGoodGatepass::query();
            $totalmrntransferqty = Mrn_Stock_Transfer::select('master_raw_material_details.Prj_Id', 'master_raw_material_details.Subprj_Id', 'master_raw_material_details.Organization', 'mrn_stock_transfer.*')
                ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id');

            if ($request->has('Manufacturing_Unit') && !empty($request->Manufacturing_Unit)) {
                $total_received_qtyQuery->where('Unit_Name', $request->Manufacturing_Unit);
                $total_FinishedGood_received_qtyQuery->where('Unit_Name', $request->Manufacturing_Unit);
                $totalmrntransferqty->where('master_raw_material_details.Prj_Id', $request->Manufacturing_Unit);
            }

            if ($request->has('Plant_Name') && !empty($request->Plant_Name)) {
                $total_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
                $total_FinishedGood_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
                $totalmrntransferqty->where('master_raw_material_details.Subprj_Id', $request->Plant_Name);
            }

            if ($request->has('Organization') && !empty($request->Organization)) {
                $total_received_qtyQuery->leftJoin('prj_organisation as p1', 'production.Organization_Name', '=', 'p1.id')
                    ->where('p1.id', $request->Organization);
                $total_FinishedGood_received_qtyQuery->leftJoin('prj_organisation as p2', 'finished_good_gatepasses.Organization_Name', '=', 'p2.id')
                    ->where('p2.id', $request->Organization);
                $totalmrntransferqty->where('master_raw_material_details.Organization', $request->Organization);
            }

            $total_received_qtyQuery->where('Raw_Material', $val->materialID);
            $total_FinishedGood_received_qtyQuery->where('Material_id', $val->materialID);
            $totalmrntransferqty->where('mrn_stock_transfer.Material_id', $val->materialID);

            $total_issue_qtyQuery = Dd_crmwtp_dispatch_item::select(
                'dd_crmwtp_dispatch_items.material_id',
                'materialmanagement_add_material.id as matid',
                'dd_crmwtp_dispatch_items.dispatch_qty',
                'dd_crmwtp_dispatch_details.created_at',
                'dd_crmwtp_dispatch_details.dispatch_status'
            )
                ->leftJoin('crmwtp_product_details', 'dd_crmwtp_dispatch_items.material_id', '=', 'crmwtp_product_details.id')
                ->leftJoin('prj_material', 'crmwtp_product_details.matrl_id', '=', 'prj_material.id')
                ->leftJoin('materialmanagement_add_material', 'prj_material.id', '=', 'materialmanagement_add_material.Material_Name')
                ->leftJoin('dd_crmwtp_dispatch_details', 'dd_crmwtp_dispatch_items.dispatch_id', '=', 'dd_crmwtp_dispatch_details.id')
                ->leftJoin('dd_crmwtp_po_details', 'dd_crmwtp_dispatch_details.so_no', '=', 'dd_crmwtp_po_details.ref_id')
                ->leftJoin('prj_organisation', 'dd_crmwtp_po_details.organisation', '=', 'prj_organisation.id')
                ->where('dd_crmwtp_dispatch_details.dispatch_status', '=', '1')
                ->where('materialmanagement_add_material.id', $val->materialID);

            if ($request->has('Organization') && !empty($request->Organization)) {
                $total_issue_qtyQuery->where('prj_organisation.id', $request->Organization);
            }

            // Opening balance
            $total_received_qty_before =
                (clone $total_received_qtyQuery)
                ->where('Production_Date', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $total_FinishedGood_received_qtyQuery)
                ->where('Transaction_Date', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('Quantity')
                + (clone $totalmrntransferqty)
                ->where('purchahedate', '<', $fromdate)
                ->where('Approve_status', 'APPROVE')
                ->sum('purchase_qty');

            $total_issue_qty_before = (clone $total_issue_qtyQuery)
                ->where('dd_crmwtp_dispatch_details.date', '<', $fromdate)
                ->sum('dispatch_qty');

            $opening_qty = $total_received_qty_before - $total_issue_qty_before;

            // Period calculations
            // $total_received_qty = (clone $total_received_qtyQuery)
            //     ->whereBetween('Production_Date', [$fromdate, $dateto])
            //     ->where('Approve_status', 'APPROVE')
            //     ->sum('Quantity')
            //     + (clone $total_FinishedGood_received_qtyQuery)
            //     ->whereBetween('Transaction_Date', [$fromdate, $dateto])
            //     ->where('Approve_status', 'APPROVE')
            //     ->sum('Quantity');
                
          $total_received_qty = (clone $total_received_qtyQuery)
            ->whereBetween('Production_Date', [$fromdate, $dateto])
            ->where('Approve_status', 'APPROVE')
            ->sum('Quantity')
            + (clone $total_FinishedGood_received_qtyQuery)
            ->whereBetween('Transaction_Date', [$fromdate, $dateto])
            ->where('Approve_status', 'APPROVE')
            ->sum('Quantity')
            + (clone $totalmrntransferqty)
            ->whereBetween('purchahedate', [$fromdate, $dateto])
            ->where('Approve_status', 'APPROVE')
            ->sum('purchase_qty');

            if ($request->has('Manufacturing_Unit') && !empty($request->Manufacturing_Unit)) {
                $total_issue_qty = 0;
            } else {
                $total_issue_qty = (clone $total_issue_qtyQuery)
                    ->whereBetween('dd_crmwtp_dispatch_details.date', [$fromdate, $dateto])
                    ->sum('dispatch_qty');
            }

            $closing_qty = $opening_qty + $total_received_qty - $total_issue_qty;

            $exportData[] = [
                "SL. No." => count($exportData) + 1, // Use count for proper sequential numbering
                "MATERIAL NAME" => $materialData->matname ?? 'N/A',
                "HSN" => $materialData->HSN_Code ?? 'N/A',
                "UOM" => $materialData->UOM ?? 'N/A',
                "LPP" => $lppamount->Rate ?? 'N/A',
                "OPENING BALANCE" => $opening_qty,
                "TOTAL RECEIVED" => $total_received_qty,
                "TOTAL ISSUED" => $total_issue_qty,
                "CLOSING BALANCE" => $closing_qty,
            ];
        }
        $file = "Material_Stock_Data.csv";

        return $this->collectionExport($exportData, $file);
    }





    public function collectionExport($exportData, $file)
    {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $file);

        $fp = fopen('php://output', 'w');
        $header = null;
        foreach ($exportData as $k => $row1) {
            if (!$header) {
                fputcsv($fp, array_keys($row1));
                fputcsv($fp, $row1);
                $header = true;
            } else {
                fputcsv($fp, $row1);
            }
        }
        fclose($fp);
    }
}
