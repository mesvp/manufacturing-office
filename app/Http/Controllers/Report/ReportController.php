<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductionProcess\{Production_Process, Production_Process_Machine, Production_Process_Stage, Production_Process_Stage_Data, Production_Process_Approve};
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom,prj_organisation};
use App\Models\{CheckBox, Admin,PlantStock};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Prj_Project,Module_Bsns_Unit,Prj_Inventory,Pur_Address};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use App\Models\Master\RawMaterial\{Master_Godown_Name,Master_Raw_Material,Master_Raw_Material_Detail};
use App\Models\BOM\{BOM, BOM_Material};
use App\Models\Production\{Production_For_Sales, Production_For_Stock,ProductionData,ProductionBatch,Production,ProductionApprove};
use App\Models\Storeissue\{Store_issue,StoreIssueApprove,StoreIssueApprovedMaterial};
use App\Models\StoreTransfer\{Mrn_Stock_Transfer, Mrn_Stock_Transfer_Detail, Mrn_Stock_Transfer_Approve};

// used for maintenance
use App\Models\Maintenance\{Maintenance_Assign, Maintenance_Assign_Data,Maintenance,MaintenanceAssignApprove};
use App\Models\Maintenance\Accidental\{MaintenancAaccidental, MaintenanceAccidentalApprove, MaintenanceAssignAccidental, MaintenanceAssignDataAccidental, MaintenanceExpensesAccidental, MaintenanceMaterialAccidental};
use Session;

class ReportController extends Controller
{
    public function storestockreport(Request $request)
        {
            $EXT = Session::get('EXT');

            $dateto = $request->input('to_date');
            $fromdate = $request->input('from_date');
            if (empty($fromdate) || empty($dateto)) {
                $fromdate = isset($fromdate) && $fromdate != '' ? $fromdate : date('Y-01-01');
                $dateto = isset($dateto) && $dateto != '' ? $dateto : date('Y-m-d');
            }
            $todate = date('Y-m-d', strtotime($dateto . ' +1 day'));

            if (isset($EXT[19]['inputer'])) {
                $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->where('Approve_status', 'APPROVE');
            } else {
                $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->where('Approve_status', 'APPROVE')
                    ->where('materialmanagement_add_material.status', 0);
            }

            $Productss = '';
            if ($request->has('Product') && $request->input('Product') != '') {
                $Productss = $request->input('Product');
                if ($Productss !== 'all') {
                    $query->where('Product', $Productss);
                }
            }

            $SubProductss = '';
            if ($request->has('Sub_Product') && $request->input('Sub_Product') != '') {
                $SubProductss = $request->input('Sub_Product');
                if ($SubProductss !== 'all') {
                    $query->where('Sub_Product', $SubProductss);
                }
            }

            $SubSubProductss = '';
            if ($request->has('Sub_Sub_Product') && $request->input('Sub_Sub_Product') != '') {
                $SubSubProductss = $request->input('Sub_Sub_Product');
                if ($SubSubProductss !== 'all') {
                    $query->where('Sub_Sub_Product', $SubSubProductss);
                }
            }

            $Materialss = '';
            if ($request->has('Material_Name') && $request->input('Material_Name') != '') {
                $Materialss = $request->input('Material_Name');
                if ($Materialss !== 'all') {
                    $query->where('materialmanagement_add_material.id', $Materialss);
                }
            }

            $Materials = $query->get();

            $Material_arr = [];
            $lpp_arr = [];
            $total_qty_arr = [];
            $total_mat_amt_arr = [];
            $total_iss_qty_arr = [];
            $opening_qty_arr = [];
            $closing_qty_arr = [];
            $closing_amt_arr = [];
            $opening_amt_arr = [];

            foreach ($Materials as $val) {
                $lppamountQuery = Master_Raw_Material::where('Material', $val->id);
                $total_received_qtyQuery = Master_Raw_Material_Detail::where('Material', $val->id)
                    ->where('Date', '>=', $fromdate)
                    ->where('Date', '<', $todate);
                $total_received_amtQuery = Master_Raw_Material_Detail::where('Material', $val->id)
                    ->where('Date', '>=', $fromdate)
                    ->where('Date', '<', $todate);
                $total_issue_qtyQuery = StoreIssueApprovedMaterial::select('store_issue_approved_material.*', 'store_requistion.Organization_Name', 'store_requistion.Manufacturing_Unit', 'store_requistion.Plant_Name')
                    ->leftJoin('store_requistion', 'store_issue_approved_material.Store_Requistion_id', '=', 'store_requistion.id')
                    ->where('store_issue_approved_material.Material_id', $val->id)
                    ->where('action', 'APPROVE')
                    ->where('store_issue_approved_material.created_at', '>=', $fromdate)
                    ->where('store_issue_approved_material.created_at', '<', $todate);
                $total_issue_mrn_stck_qty= Mrn_Stock_Transfer::select('master_raw_material_details.Prj_Id', 'master_raw_material_details.Subprj_Id', 'master_raw_material_details.Organization','master_raw_material_details.Godown_Name', 'mrn_stock_transfer.*')
                ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
                        ->where('mrn_stock_transfer.Material_id', $val->id)
                        ->where('Approve_status', 'APPROVE');

                // Apply filters to all queries (including before-date clones)
                $received_qty_before = clone $total_received_qtyQuery;
                $received_amt_before = clone $total_received_amtQuery;
                $issue_qty_before = clone $total_issue_qtyQuery;
                $issue_mrn_stck_qty_before = clone $total_issue_mrn_stck_qty;

                if (isset($request->Organization) && $request->Organization != '') {
                    $lppamountQuery->where('Organization', $request->Organization);
                    $total_received_qtyQuery->where('Organization', $request->Organization);
                    $total_received_amtQuery->where('Organization', $request->Organization);
                    $total_issue_qtyQuery->where('Organization_Name', $request->Organization);
                    $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Organization_Name', $request->Organization);
                    $received_qty_before->where('Organization', $request->Organization);
                    $received_amt_before->where('Organization', $request->Organization);
                    $issue_qty_before->where('Organization_Name', $request->Organization);
                    $issue_mrn_stck_qty_before->where('mrn_stock_transfer.Organization_Name', $request->Organization);
                }
                if (isset($request->Godown_Name) && $request->Godown_Name != '') {
                    $lppamountQuery->where('Godown_Name', $request->Godown_Name);
                    $total_received_qtyQuery->where('Godown_Name', $request->Godown_Name);
                    $total_received_amtQuery->where('Godown_Name', $request->Godown_Name);
                    $total_issue_qtyQuery->where('Godown_Name', $request->Godown_Name);
                    $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Godown_Name', $request->Godown_Name);
                    $received_qty_before->where('Godown_Name', $request->Godown_Name);
                    $received_amt_before->where('Godown_Name', $request->Godown_Name);
                    $issue_qty_before->where('Godown_Name', $request->Godown_Name);
                    $issue_mrn_stck_qty_before->where('mrn_stock_transfer.Godown_Name', $request->Godown_Name);
                }
                if (isset($request->Plant_Name) && $request->Plant_Name != '') {
                    $total_issue_qtyQuery->where('Plant_Name', $request->Plant_Name);
                    $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Plant_Name', $request->Plant_Name);
                    $issue_qty_before->where('Plant_Name', $request->Plant_Name);
                    $issue_mrn_stck_qty_before->where('mrn_stock_transfer.Plant_Name', $request->Plant_Name);
                }
                if (isset($request->Manufacturing_Unit) && $request->Manufacturing_Unit != '') {
                    $total_issue_qtyQuery->where('Manufacturing_Unit', $request->Manufacturing_Unit);
                    $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Manufacturing_Unit', $request->Manufacturing_Unit);
                    $issue_qty_before->where('Manufacturing_Unit', $request->Manufacturing_Unit);
                    $issue_mrn_stck_qty_before->where('mrn_stock_transfer.Manufacturing_Unit', $request->Manufacturing_Unit);
                }

                // Calculate opening quantities and amounts before fromdate (with filters)
                $total_received_qty_before = $received_qty_before->where('Date', '<', $fromdate)->sum('Quantity');
                $total_received_amt_before = $received_amt_before->where('Date', '<', $fromdate)->sum('Amount');
                $total_issue_qty_before = $issue_qty_before->where('store_issue_approved_material.created_at', '<', $fromdate)->sum('issueQTY')
                    + $issue_mrn_stck_qty_before->where('mrn_stock_transfer.purchahedate', '<', $fromdate)->sum('purchase_qty');

                $total_received_qty = null;
                $total_received_amt = null;
                $opening_qty = null;
                $opening_amt = null;

                if (isset($request->Manufacturing_Unit) && $request->Manufacturing_Unit != '') {
                    $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                    $total_received_qty = "n/a";
                    $total_received_amt = "n/a";
                } elseif (isset($request->Plant_Name) && $request->Plant_Name != '') {
                    $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                    $total_received_qty = "n/a";
                    $total_received_amt = "n/a";
                } else {
                    if ($fromdate && $todate) {
                        $lppamountQuery->whereBetween('Date', [$fromdate, $todate]);
                        $total_received_qtyQuery->whereBetween('Date', [$fromdate, $todate]);
                        $total_received_amtQuery->whereBetween('Date', [$fromdate, $todate]);
                        $total_issue_qtyQuery->whereBetween('store_issue_approved_material.created_at', [$fromdate, $todate]);
                        $total_issue_mrn_stck_qty->whereBetween('mrn_stock_transfer.purchahedate', [$fromdate, $todate]);
                    }
                    if (isset($request->Organization) && $request->Organization != '') {
                        $lppamountQuery->where('Organization', $request->Organization);
                        $total_received_qtyQuery->where('Organization', $request->Organization);
                        $total_received_amtQuery->where('Organization', $request->Organization);
                        $total_issue_qtyQuery->where('Organization_Name', $request->Organization);
                        $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Organization_Name', $request->Organization);
                    }
                    if (isset($request->Godown_Name) && $request->Godown_Name != '') {
                        $lppamountQuery->where('Godown_Name', $request->Godown_Name);
                        $total_received_qtyQuery->where('Godown_Name', $request->Godown_Name);
                        $total_received_amtQuery->where('Godown_Name', $request->Godown_Name);
                        $total_issue_qtyQuery->where('Godown_Name', $request->Godown_Name);
                        $total_issue_mrn_stck_qty->where('mrn_stock_transfer.Godown_Name', $request->Godown_Name);
                    }
                    //return $total_received_qtyQuery->first();

                    $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                    $total_received_qty = $total_received_qtyQuery->sum('Quantity');
                    $total_received_amt = $total_received_amtQuery->sum('Amount');
                    $opening_qty = $total_received_qty_before - $total_issue_qty_before;
                    $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;

                    if (is_numeric($total_received_amt_before) && is_numeric($total_issue_qty_before)) {
                        $opening_amt = $total_received_amt_before - ($total_issue_qty_before * $rate);
                    } else {
                        $opening_amt = "n/a";
                    }
                }
                
                $total_issue_qty = $total_issue_qtyQuery->sum('issueQTY')+$total_issue_mrn_stck_qty->sum('purchase_qty');

                $lpp_arr[$val->id] = ($lppamount && is_numeric($lppamount->Rate)) ? $lppamount->Rate : 0;
                $total_qty_arr[$val->id] = (is_numeric($total_received_qty)) ? $total_received_qty : 0;
                $total_mat_amt_arr[$val->id] = (is_numeric($total_received_amt)) ? $total_received_amt : 0;
                $total_iss_qty_arr[$val->id] = (is_numeric($total_issue_qty)) ? $total_issue_qty : 0;
                $opening_qty_arr[$val->id] = (is_numeric($opening_qty)) ? $opening_qty : 0;
                $opening_amt_arr[$val->id] = (is_numeric($opening_amt)) ? $opening_amt : 0;
                
                // Calculate closing balance: Opening Balance + Total Received - Total Issued
                $closing_balance = 0;
                if (is_numeric($opening_qty) && is_numeric($total_received_qty) && is_numeric($total_issue_qty)) {
                    $closing_balance = $opening_qty + $total_received_qty - $total_issue_qty;
                }
                $closing_qty_arr[$val->id] = $closing_balance;
                // $closing_amt_arr[$val->id] = (is_numeric($total_received_amt) && is_numeric($total_issue_qty) && $lppamount) ? ($total_received_amt - ($total_issue_qty * ($lppamount->Rate + $lppamount->GST / 100))) : 0;
                $rate_with_gst = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST))
                    ? $lppamount->Rate * (1 + $lppamount->GST / 100)
                    : 0;

                // Calculate closing amount: Opening Amount + Total Received Amount - Total Issue Amount
                $closing_amount = 0;
                if (is_numeric($opening_amt) && is_numeric($total_received_amt) && is_numeric($total_issue_qty)) {
                    $closing_amount = $opening_amt + $total_received_amt - ($total_issue_qty * $rate_with_gst);
                }
                $closing_amt_arr[$val->id] = $closing_amount;
                
                // if ($lppamount && is_numeric($total_issue_qty)) {
                //     $issueprice = $total_issue_qty * ($lppamount->Rate + $lppamount->GST / 100);
                // } else {
                //     $issueprice = 0;
                // }
                if ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST) && is_numeric($total_issue_qty)) {
                    $issueprice = $total_issue_qty * $lppamount->Rate * (1 + $lppamount->GST / 100);
                } else {
                    $issueprice = 0;
                }
                $val->issueprice = $issueprice;

                $Material_arr[] = $val;
            }

            $Product = Factory_Product::all();
            $Organization = prj_organisation::all();
            $Godown_Name = Prj_Inventory::all();
            $Manufacturing_unit = prj_project::all();
            $plant_name = Prj_Subproject::all();
            $Sub_Product = Factory_Sub_Product::all();
            $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
            $UOM = Factory_Uom::all();
            $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
            $Material_Name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name')
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->where('Approve_status', 'APPROVE')
                ->get();

            $Dropdown = Production_Process::orderBy('id', 'DESC')->get();

            return view('Report/Storestockreport', [
                'Materials' => $Material_arr,
                'lpp_arr' => $lpp_arr,
                'total_qty_arr' => $total_qty_arr,
                'total_mat_amt_arr' => $total_mat_amt_arr,
                'total_iss_qty_arr' => $total_iss_qty_arr,
                'closing_qty_arr' => $closing_qty_arr,
                'closing_amt_arr' => $closing_amt_arr,
                'opening_qty_arr' => $opening_qty_arr,
                'opening_amt_arr' => $opening_amt_arr,
                'fromdate' => $fromdate,
                'todate' => $dateto,
                'Dropdown' => $Dropdown,
                'Productss' => $Productss,
                'SubProductss' => $SubProductss,
                'SubSubProductss' => $SubSubProductss,
                'Product' => $Product,
                'Sub_Product' => $Sub_Product,
                'Sub_Sub_Product' => $Sub_Sub_Product,
                'UOM' => $UOM,
                'Material_Name' => $Material_Name,
                'Materialss' => $Materialss,
                'Organization' => $Organization,
                'Godown_Name' => $Godown_Name,
                'Manufacturing_unit' => $Manufacturing_unit,
                'plant_name' => $plant_name
            ]);
        }



    public function storestockreportdetails(Request $request ,$id)
    {
        $EXT = Session::get('EXT');

        // $dateto = $request->input('to_date');
        // $fromdate = $request->input('from_date');
        // $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        if (empty($fromdate) || empty($dateto)) {
            $fromdate = date('Y-m-d', strtotime('-1 month'));
            $dateto = date('Y-m-d');
        }
        $todate = date('Y-m-d', strtotime($dateto . ' +1 day'));
        $Receivedmaterials=[];
        $Issuedmaterials=[];
        $Materialss = '';
        $Productss = '';

    
                if (isset($EXT[19]['inputer'])) {
                      $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation as from_org','prj_inventory.inventory_name as from_godown','master_raw_material_details.Project as fromproject','master_raw_material_details.Sub_Project as fromsubproject')
                    ->leftJoin('materialmanagement_add_material','master_raw_material_details.Material','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','master_raw_material_details.Organization','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','master_raw_material_details.Godown_Name','=','prj_inventory.id')
                    ->where('master_raw_material_details.Material',$id);

                    $query2 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation as to_org','prj_inventory.inventory_name as to_godown','prj_project.pname as toproject','prj_subproject.spname as tosubproject','mrm_from_org.organisation as from_org','mrm_from_godown.inventory_name as from_godown')
                    ->leftJoin('store_requistion','store_issue_approved_material.Store_Requistion_id','=','store_requistion.id')
                    ->leftJoin('materialmanagement_add_material','store_issue_approved_material.Material_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','store_requistion.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','store_requistion.Godown_Name','=','prj_inventory.id')
                    ->leftJoin('prj_project','store_requistion.Manufacturing_Unit','=','prj_project.id')
                    ->leftJoin('prj_subproject','store_requistion.Plant_Name','=','prj_subproject.id')
                    ->leftJoin('master_raw_material','master_raw_material.Material','=','store_issue_approved_material.Material_id')
                    ->leftJoin('prj_organisation as mrm_from_org','master_raw_material.Organization','=','mrm_from_org.id')
                    ->leftJoin('prj_inventory as mrm_from_godown','master_raw_material.Godown_Name','=','mrm_from_godown.id')
                    ->where('store_issue_approved_material.Material_id',$id)
                    ->where('action', 'APPROVE');

                      $query3 = Mrn_Stock_Transfer::select('master_raw_material_details.Prj_Id', 'prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','of.organisation as from_org','gf.inventory_name as from_godown','tf.organisation as to_org','gt.inventory_name as to_godown','prj_project.pname as fromproject','prj_subproject.spname as fromsubproject','master_raw_material_details.Supplier_Name','master_raw_material_details.Mrn_No','master_raw_material_details.Invoice_No','master_raw_material_details.Mrn_Date','mrn_stock_transfer.*')
                    ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
                    ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->leftJoin('prj_organisation as of', 'master_raw_material_details.Organization', '=', 'of.id')
                    ->leftJoin('prj_organisation as tf', 'mrn_stock_transfer.Organization_Name', '=', 'tf.id')
                    ->leftJoin('prj_project', 'master_raw_material_details.Prj_Id', '=', 'prj_project.id')
                    ->leftJoin('prj_subproject', 'master_raw_material_details.Subprj_Id', '=', 'prj_subproject.id')
                    ->leftJoin('prj_inventory as gf','master_raw_material_details.Godown_Name','=','gf.id')
                    ->leftJoin('prj_inventory as gt','mrn_stock_transfer.Godown_Name','=','gt.id')
                    ->where('mrn_stock_transfer.Material_id', $id)
                    ->where('mrn_stock_transfer.Approve_status', 'APPROVE');  
                } else {
                     $query = Master_Raw_Material_Detail::select('master_raw_material_details.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation as from_org','prj_inventory.inventory_name as from_godown','master_raw_material_details.Project as fromproject','master_raw_material_details.Sub_Project as fromsubproject')
                    ->leftJoin('materialmanagement_add_material','master_raw_material_details.Material','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','master_raw_material_details.Organization','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','master_raw_material_details.Godown_Name','=','prj_inventory.id')
                    ->where('master_raw_material_details.Material',$id);

                    $query2 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation as to_org','prj_inventory.inventory_name as to_godown','prj_project.pname as toproject','prj_subproject.spname as tosubproject','mrm_from_org.organisation as from_org','mrm_from_godown.inventory_name as from_godown')
                    ->leftJoin('store_requistion','store_issue_approved_material.Store_Requistion_id','=','store_requistion.id')
                    ->leftJoin('materialmanagement_add_material','store_issue_approved_material.Material_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','store_requistion.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','store_requistion.Godown_Name','=','prj_inventory.id')
                    ->leftJoin('prj_project','store_requistion.Manufacturing_Unit','=','prj_project.id')
                    ->leftJoin('prj_subproject','store_requistion.Plant_Name','=','prj_subproject.id')
                    ->leftJoin('master_raw_material','master_raw_material.Material','=','store_issue_approved_material.Material_id')
                    ->leftJoin('prj_organisation as mrm_from_org','master_raw_material.Organization','=','mrm_from_org.id')
                    ->leftJoin('prj_inventory as mrm_from_godown','master_raw_material.Godown_Name','=','mrm_from_godown.id')
                    ->where('store_issue_approved_material.Material_id',$id)
                    ->where('action', 'APPROVE');

                    $query3 = Mrn_Stock_Transfer::select('master_raw_material_details.Prj_Id', 'prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','of.organisation as from_org','gf.inventory_name as from_godown','tf.organisation as to_org','gt.inventory_name as to_godown','prj_project.pname as fromproject','prj_subproject.spname as fromsubproject','master_raw_material_details.Supplier_Name','master_raw_material_details.Mrn_No','master_raw_material_details.Invoice_No','master_raw_material_details.Mrn_Date','mrn_stock_transfer.*')
                    ->leftJoin('master_raw_material_details', 'mrn_stock_transfer.tr_id', '=', 'master_raw_material_details.id')
                    ->leftJoin('materialmanagement_add_material', 'mrn_stock_transfer.Material_id', '=', 'materialmanagement_add_material.id')
                    ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                    ->leftJoin('prj_organisation as of', 'master_raw_material_details.Organization', '=', 'of.id')
                    ->leftJoin('prj_organisation as tf', 'mrn_stock_transfer.Organization_Name', '=', 'tf.id')
                    ->leftJoin('prj_project', 'master_raw_material_details.Prj_Id', '=', 'prj_project.id')
                    ->leftJoin('prj_subproject', 'master_raw_material_details.Subprj_Id', '=', 'prj_subproject.id')
                    ->leftJoin('prj_inventory as gf','master_raw_material_details.Godown_Name','=','gf.id')
                    ->leftJoin('prj_inventory as gt','mrn_stock_transfer.Godown_Name','=','gt.id')
                    ->where('mrn_stock_transfer.Material_id', $id)
                    ->where('mrn_stock_transfer.Approve_status', 'APPROVE');    
                }
                if ($fromdate && $todate) {
                    $query->where('master_raw_material_details.Date', '>=', $fromdate)
                        ->where('master_raw_material_details.Date', '<', $todate);
                    $query2->where('store_issue_approved_material.created_at', '>=', $fromdate)
                        ->where('store_issue_approved_material.created_at', '<', $todate);
                    $query3->where('mrn_stock_transfer.purchahedate', '>=', $fromdate)
                        ->where('mrn_stock_transfer.purchahedate', '<', $todate);
                }
                
                if ($request->has('Organization') && $request->input('Organization') != '') {
                    $Organization = $request->input('Organization');
                    if ($Organization !== 'all') {
                        $query->where('master_raw_material_details.Organization', $Organization);
                        $query2->where('store_requistion.Organization_Name', $Organization);
                        $query3->where('master_raw_material_details.Organization', $Organization);
                    }
                }
                if ($request->has('Godown_Name') && $request->input('Godown_Name') != '') {
                    $Godown_Name = $request->input('Godown_Name');
                    if ($Godown_Name !== 'all') {
                        $query->where('master_raw_material_details.Godown_Name', $Godown_Name);
                        $query2->where('store_requistion.Godown_Name', $Godown_Name);
                        $query3->where('master_raw_material_details.Godown_Name', $Godown_Name);
                    }
                }
                if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
                    $plant = $request->input('Plant_Name');
                    if ($plant !== 'all') {
                        $query->where('master_raw_material_details.Subprj_Id', $plant);
                        $query2->where('store_requistion.Plant_Name', $plant);
                        $query3->where('master_raw_material_details.Subprj_Id', $plant);
                    }
                }
                
                if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
                    $project = $request->input('Manufacturing_Unit');
                    if ($project !== 'all') {
                        $query->where('master_raw_material_details.Prj_Id', $project);
                        $query2->where('store_requistion.Manufacturing_Unit', $project);
                        $query3->where('master_raw_material_details.Prj_Id', $project);
                    }
                }
                
                if ($request->has('Material_Name') && $request->input('Material_Name') != '') {
                    $Materialss = $request->input('Material_Name');
                    if ($Materialss !== 'all') {
                        $query->where('master_raw_material_details.Material', $Materialss);
                        $query2->where('store_issue_approved_material.Material_id', $Materialss);
                        $query3->where('mrn_stock_transfer.Material_id', $Materialss);

                    }
                }

                $Receivedmaterials = $query->get();
                $Issuedmaterials = $query2->get();
                 $Issuedmaterials = $Issuedmaterials->merge($query3->get());

                // dd($Issuedmaterials);
         


        $Product = Factory_Product::all();
        $Organization = prj_organisation::all();
        $Godown_Name = Prj_Inventory::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Material_Name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('Approve_status', 'APPROVE')
                ->get();

        $Dropdown = Production_Process::orderBy('id', 'DESC')->get();

        return view('Report/storestockreportdetails', ['Receivedmaterials' => $Receivedmaterials,'Issuedmaterials'=>$Issuedmaterials,'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Material_Name' => $Material_Name, 'Materialss' => $Materialss,'Organization'=>$Organization,'Godown_Name'=>$Godown_Name,'Manufacturing_unit'=>$Manufacturing_unit,'plant_name'=>$plant_name,'fromdate' => $fromdate,'todate' => $dateto,'matid'=>$id]);
    }

    public function plantstockreport(Request $request)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        if (empty($fromdate) || empty($dateto)) {
            $fromdate = date('Y-m-d', strtotime('-1 month'));
            $dateto = date('Y-m-d');
        }
        $todate = date('Y-m-d', strtotime($dateto . ' +1 day'));

        if (isset($EXT[19]['inputer'])) {
            $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->where('Approve_status', 'APPROVE');
        } else {
            $query = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
                ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
                ->where('Approve_status', 'APPROVE')
                ->where('materialmanagement_add_material.status', 0);
        }

        $Materialss = '';
        if ($request->has('Material_Name') && $request->input('Material_Name') != '') {
            $Materialss = $request->input('Material_Name');
            if ($Materialss !== 'all') {
                $multi = function($val) {
                    if (is_array($val)) return $val;
                    if (strpos($val, ',') !== false) return array_map('trim', explode(',', $val));
                    return [$val];
                };
                $matArr = $multi($Materialss);
                $query->whereIn('materialmanagement_add_material.id', $matArr);
            }
        }

        $Materials = $query->get();

        $Material_arr = [];
        $lpp_arr = [];
        $total_qty_arr = [];
        $total_iss_qty_arr = [];
        $opening_qty_arr = [];
        $opening_amt_arr = [];
        $closing_qty_arr = [];
        $closing_amt_arr = [];
        $production_qty_arr = [];

        foreach ($Materials as $val) {

            // Build base queries for main and before-date calculations
            $lppamountQuery = Master_Raw_Material::where('Material', $val->id);
            $total_received_qtyQueryBase = StoreIssueApprovedMaterial::select('store_issue_approved_material.*', 'store_requistion.Organization_Name', 'store_requistion.Manufacturing_Unit', 'store_requistion.Plant_Name')
                ->leftJoin('store_requistion', 'store_issue_approved_material.Store_Requistion_id', '=', 'store_requistion.id')
                ->where('store_issue_approved_material.Material_id', $val->id)
                ->where('action', 'APPROVE');
            $productionBase = Production::select('production.Quantity')
                ->where('production.Raw_Material', $val->id)
                ->where('Approve_status', 'APPROVE');
            $total_received_amtQuery = Master_Raw_Material_Detail::where('Material', $val->id);
            $total_issue_qtyQueryBase = ProductionData::select('production_data.*', 'production.Organization_Name', 'production.Unit_Name', 'production.Plant_Name')
                ->leftJoin('production', 'production_data.productionID', '=', 'production.id')
                ->where('production_data.RawMaterial_id', $val->id)
                ->where('production.Approve_status', 'APPROVE');
            $closing_balanceQuery = PlantStock::where('materialID', $val->id)->where('type', 0);
            $closing_bal_amountQuery = Master_Raw_Material::where('Material', $val->id);

            // Apply all filters to all queries (including before-date clones)
            $filterCallbacks = function($q) use ($request) {
                $multi = function($val) {
                    if (is_array($val)) return $val;
                    if (strpos($val, ',') !== false) return array_map('trim', explode(',', $val));
                    return [$val];
                };
                if ($request->has('Organization') && $request->Organization != '') {
                    $org = $multi($request->Organization);
                    $q->whereIn('store_requistion.Organization_Name', $org);
                }
                if ($request->has('Manufacturing_Unit') && $request->Manufacturing_Unit != '') {
                    $unit = $multi($request->Manufacturing_Unit);
                    $q->whereIn('Manufacturing_Unit', $unit);
                }
                if ($request->has('Plant_Name') && $request->Plant_Name != '') {
                    $plant = $multi($request->Plant_Name);
                    $q->whereIn('Plant_Name', $plant);
                }
                if ($request->has('Godown_Name') && $request->Godown_Name != '') {
                    $godown = $multi($request->Godown_Name);
                    $q->whereIn('Godown_Name', $godown);
                }
            };
            $filterProd = function($q) use ($request) {
                $multi = function($val) {
                    if (is_array($val)) return $val;
                    if (strpos($val, ',') !== false) return array_map('trim', explode(',', $val));
                    return [$val];
                };
                if ($request->has('Organization') && $request->Organization != '') {
                    $org = $multi($request->Organization);
                    $q->whereIn('production.Organization_Name', $org);
                }
                if ($request->has('Manufacturing_Unit') && $request->Manufacturing_Unit != '') {
                    $unit = $multi($request->Manufacturing_Unit);
                    $q->whereIn('Unit_Name', $unit);
                }
                if ($request->has('Plant_Name') && $request->Plant_Name != '') {
                    $plant = $multi($request->Plant_Name);
                    $q->whereIn('Plant_Name', $plant);
                }
                if ($request->has('Godown_Name') && $request->Godown_Name != '') {
                    $godown = $multi($request->Godown_Name);
                    $q->whereIn('production.Godown_Name', $godown);
                }
            };

            // Main queries (with date range)
            $total_received_qtyQuery = (clone $total_received_qtyQueryBase);
            $total_received_qtyQuery = $total_received_qtyQuery->where(function($q) use ($filterCallbacks) { $filterCallbacks($q); });
            $production = (clone $productionBase);
            $production = $production->where(function($q) use ($filterProd) { $filterProd($q); });
            $total_issue_qtyQuery = (clone $total_issue_qtyQueryBase);
            $total_issue_qtyQuery = $total_issue_qtyQuery->where(function($q) use ($filterProd) { $filterProd($q); });

            if ($fromdate && $todate) {
                $total_received_qtyQuery->where('store_issue_approved_material.created_at', '>=', $fromdate)
                    ->where('store_issue_approved_material.created_at', '<', $todate);
                $total_issue_qtyQuery->where('production_data.created_at', '>=', $fromdate)
                    ->where('production_data.created_at', '<', $todate);
                $production->where('Production_Date', '>=', $fromdate)
                    ->where('Production_Date', '<', $todate);
            }

            // Before-date queries (for opening balance)
            $received_before = (clone $total_received_qtyQueryBase)->where(function($q) use ($filterCallbacks) { $filterCallbacks($q); });
            $production_before = (clone $productionBase)->where(function($q) use ($filterProd) { $filterProd($q); });
            $issue_before = (clone $total_issue_qtyQueryBase)->where(function($q) use ($filterProd) { $filterProd($q); });
            $total_received_qty_before = $received_before->where('store_issue_approved_material.created_at', '<', $fromdate)->sum('issueQTY');
            $production_qty_before = $production_before->where('Production_Date', '<', $fromdate)->sum('Quantity');
            $total_issue_qty_before = $issue_before->where('production_data.created_at', '<', $fromdate)->sum('TotalQty');

            $total_received_qty = null;
            $closing_balance = null;
            $opening_qty = null;
            $opening_amt = null;

            if (isset($request->Manufacturing_Unit) && $request->Manufacturing_Unit != '') {
                $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                $total_received_qtyQuery->where('Manufacturing_Unit', $request->Manufacturing_Unit);
                $closing_balanceQuery->where('Manufacturing_Unit', $request->Manufacturing_Unit);
                $production->where('Unit_Name', $request->Manufacturing_Unit);
                $closing_balance = $closing_balanceQuery->sum('stock');
                $total_received_qty = $total_received_qtyQuery->sum('issueQTY');
                $production_quantity = $production->sum('Quantity');
                
                // Calculate opening balance for Manufacturing Unit filter
                $opening_qty = ($total_received_qty_before + $production_qty_before) - $total_issue_qty_before;
                $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;
                if (is_numeric($opening_qty) && $rate > 0) {
                    $opening_amt = $opening_qty * $rate;
                } else {
                    $opening_amt = 0;
                }
            } elseif (isset($request->Plant_Name) && $request->Plant_Name != '') {
                $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                $total_received_qtyQuery->where('Plant_Name', $request->Plant_Name);
                $closing_balanceQuery->where('plantID', $request->Plant_Name);
                $production->where('Plant_Name', $request->Plant_Name);
                $closing_balance = $closing_balanceQuery->sum('stock');
                $total_received_qty = $total_received_qtyQuery->sum('issueQTY');
                $production_quantity = $production->sum('Quantity');
                
                // Calculate opening balance for Plant filter
                $opening_qty = ($total_received_qty_before + $production_qty_before) - $total_issue_qty_before;
                $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;
                if (is_numeric($opening_qty) && $rate > 0) {
                    $opening_amt = $opening_qty * $rate;
                } else {
                    $opening_amt = 0;
                }
            } elseif (isset($request->Godown_Name) && $request->Godown_Name != '') {
                $lppamountQuery->where('Godown_Name', $request->Godown_Name);
                $total_received_qtyQuery->where('Godown_Name', $request->Godown_Name);
                $total_issue_qty = "n/a";
                $closing_balance = "n/a";
                $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                $total_received_qty = $total_received_qtyQuery->sum('issueQTY');
                $production_quantity = $production->sum('Quantity');
                
                // Calculate opening balance for Godown filter
                $opening_qty = ($total_received_qty_before + $production_qty_before) - $total_issue_qty_before;
                $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;
                if (is_numeric($opening_qty) && $rate > 0) {
                    $opening_amt = $opening_qty * $rate;
                } else {
                    $opening_amt = 0;
                }
            } elseif (isset($request->Organization) && $request->Organization != '') {
                $lppamountQuery->where('Organization', $request->Organization);
                $total_received_qtyQuery->where('Organization_Name', $request->Organization);
                $total_received_amtQuery->where('Organization_Name', $request->Organization);
                $total_issue_qtyQuery->where('Organization_Name', $request->Organization);
                $production->where('Organization_Name', $request->Organization);
                $closing_balance = 0;
                $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                $total_received_qty = $total_received_qtyQuery->sum('issueQTY');
                $production_quantity = $production->sum('Quantity');
                
                // Calculate opening balance for Organization filter
                $opening_qty = ($total_received_qty_before + $production_qty_before) - $total_issue_qty_before;
                $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;
                if (is_numeric($opening_qty) && $rate > 0) {
                    $opening_amt = $opening_qty * $rate;
                } else {
                    $opening_amt = 0;
                }
            } else {
                if ($fromdate && $todate) {
                    $lppamountQuery->whereBetween('Date', [$fromdate, $todate]);
                    $total_received_qtyQuery->whereBetween('store_issue_approved_material.created_at', [$fromdate, $todate]);
                    $total_received_amtQuery->whereBetween('Date', [$fromdate, $todate]);
                    $total_issue_qtyQuery->whereBetween('production_data.created_at', [$fromdate, $todate]);
                    $closing_balanceQuery->whereBetween('created_at', [$fromdate, $todate]);
                    $closing_bal_amountQuery->whereBetween('Date', [$fromdate, $todate]);
                    $production->whereBetween('Production_Date', [$fromdate, $todate]);
                }
                $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
                $total_received_qty = $total_received_qtyQuery->sum('issueQTY');
                $closing_balance = $closing_balanceQuery->sum('stock');
                $production_quantity = $production->sum('Quantity');
                
                // Calculate opening balance: Previous received materials (store issues to plant) + previous production - previous consumed
                $opening_qty = ($total_received_qty_before + $production_qty_before) - $total_issue_qty_before;
                
                // Calculate opening amount
                $rate = ($lppamount && is_numeric($lppamount->Rate) && is_numeric($lppamount->GST)) ? ($lppamount->Rate + $lppamount->GST / 100) : 0;
                if (is_numeric($opening_qty) && $rate > 0) {
                    $opening_amt = $opening_qty * $rate;
                } else {
                    $opening_amt = 0;
                }
            }
            //return $closing_balance;
            // Sum both issue quantities: from ProductionData (TotalQty) and from Production (Quantity)
            $total_issue_qty = $total_issue_qtyQuery->sum('TotalQty') + $production->sum('Quantity');

            $lpp_arr[$val->id] = $lppamount !== "n/a" ? ($lppamount ? $lppamount->Rate : null) : "n/a";
            $total_qty_arr[$val->id] = $total_received_qty !== "n/a" ? $total_received_qty : "n/a";
            $total_iss_qty_arr[$val->id] = $total_issue_qty ?: null;
            $opening_qty_arr[$val->id] = (is_numeric($opening_qty)) ? $opening_qty : 0;
            $opening_amt_arr[$val->id] = (is_numeric($opening_amt)) ? $opening_amt : 0;
            $closing_qty_arr[$val->id] = is_numeric($opening_qty) && is_numeric($total_received_qty) && is_numeric($total_issue_qty) 
                ? ($opening_qty + $total_received_qty + $production_quantity - $total_issue_qty) 
                : (($total_qty_arr[$val->id] !== "n/a" && $total_iss_qty_arr[$val->id] !== null) 
                   ? ($total_qty_arr[$val->id] - $total_iss_qty_arr[$val->id]) 
                   : 0);
            $production_qty_arr[$val->id] = $production_quantity !== "n/a" ? $production_quantity : "n/a";

            if ($lppamount && is_numeric($total_received_qty)) {
                $receiveprice = $total_received_qty * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $receiveprice = null;
            }
            $val->receiveprice = $receiveprice;

            if ($lppamount && is_numeric($total_issue_qty)) {
                $issueprice = $total_issue_qty * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $issueprice = null;
            }
            $val->issueprice = $issueprice;

            // Calculate closing amount: Opening Amount + Received Amount - Issued Amount
            if ($lppamount && is_numeric($opening_amt) && is_numeric($receiveprice) && is_numeric($issueprice)) {
                $closingprice = $opening_amt + $receiveprice - $issueprice;
            } else {
                $closingprice = null;
            }
            $val->closingprice = $closingprice;
            $closing_amt_arr[$val->id] = $closingprice;

            $Material_arr[] = $val;
        }

        $Product = Factory_Product::all();
        $Organization = prj_organisation::all();
        $Godown_Name = Prj_Inventory::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Material_Name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->where('Approve_status', 'APPROVE')
            ->get();

        $Dropdown = Production_Process::orderBy('id', 'DESC')->get();

        return view('Report/plantstockreport', [
            'Materials' => $Material_arr,
            'lpp_arr' => $lpp_arr,
            'total_qty_arr' => $total_qty_arr,
            'total_iss_qty_arr' => $total_iss_qty_arr,
            'opening_qty_arr' => $opening_qty_arr,
            'opening_amt_arr' => $opening_amt_arr,
            'closing_qty_arr' => $closing_qty_arr,
            'closing_amt_arr' => $closing_amt_arr,
            'fromdate' => $fromdate,
            'todate' => $dateto,
            'Dropdown' => $Dropdown,
            'Product' => $Product,
            'Sub_Product' => $Sub_Product,
            'Sub_Sub_Product' => $Sub_Sub_Product,
            'UOM' => $UOM,
            'Material_Name' => $Material_Name,
            'Materialss' => $Materialss,
            'Organization' => $Organization,
            'Godown_Name' => $Godown_Name,
            'Manufacturing_unit' => $Manufacturing_unit,
            'plant_name' => $plant_name,
            'production_qty_arr' => $production_qty_arr
        ]);
    }

    public function plantstockreportdetails(Request $request, $id)
    {
        $EXT = Session::get('EXT');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        if (empty($fromdate) || empty($dateto)) {
            $fromdate = date('Y-m-d', strtotime('-1 month'));
            $dateto = date('Y-m-d');
        }
        $todate = date('Y-m-d', strtotime($dateto . ' +1 day'));


        $Receivedmaterials=[];
        $Issuedmaterials=[];
        $Materialss = '';
        $Productss = '';

        
                if (isset($EXT[19]['inputer'])) {
                    //$query = Production_Process::orderBy('id', 'DESC');

                    $query2 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation','prj_inventory.inventory_name','prj_project.pname','prj_subproject.spname')
                    ->leftJoin('store_requistion','store_issue_approved_material.Store_Requistion_id','=','store_requistion.id')
                    ->leftJoin('materialmanagement_add_material','store_issue_approved_material.Material_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','store_requistion.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','store_requistion.Godown_Name','=','prj_inventory.id')
                    ->leftJoin('prj_project','store_requistion.Manufacturing_Unit','=','prj_project.id')
                    ->leftJoin('prj_subproject','store_requistion.Plant_Name','=','prj_subproject.id')
                    ->where('store_issue_approved_material.Material_id',$id)
                    ->where('action','APPROVE');

                    $query3= Production::select('production.Quantity as issueQTY','production.Raw_Material','prj_project.pname','prj_subproject.spname','prj_organisation.organisation','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','production.Production_Date')
                        ->where('production.Raw_Material', $id)
                        ->leftJoin('prj_organisation','production.Organization_Name','=','prj_organisation.id')
                        ->leftJoin('prj_project','production.Unit_Name','=','prj_project.id')
                        ->leftJoin('prj_subproject','production.Plant_Name','=','prj_subproject.id')
                            ->leftJoin('materialmanagement_add_material','production.Raw_Material','=','materialmanagement_add_material.id')
                            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('production.Approve_status', 'APPROVE');

                    $query = ProductionData::select('production_data.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation','prj_project.pname','prj_subproject.spname')
                    ->leftJoin('production', 'production_data.productionID', '=', 'production.id')
                    ->leftJoin('materialmanagement_add_material','production_data.RawMaterial_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','production.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_project','production.Unit_Name','=','prj_project.id')
                    ->leftJoin('prj_subproject','production.Plant_Name','=','prj_subproject.id')
                    ->where('production_data.RawMaterial_id',$id)
                    ->where('production.Approve_status', 'APPROVE');
                    
                } else {

                    $query2 = StoreIssueApprovedMaterial::select('store_issue_approved_material.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation','prj_inventory.inventory_name','prj_project.pname','prj_subproject.spname')
                    ->leftJoin('store_requistion','store_issue_approved_material.Store_Requistion_id','=','store_requistion.id')
                    ->leftJoin('materialmanagement_add_material','store_issue_approved_material.Material_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','store_requistion.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_inventory','store_requistion.Godown_Name','=','prj_inventory.id')
                    ->leftJoin('prj_project','store_requistion.Manufacturing_Unit','=','prj_project.id')
                    ->leftJoin('prj_subproject','store_requistion.Plant_Name','=','prj_subproject.id')
                    ->where('store_issue_approved_material.Material_id',$id)
                    ->where('action','APPROVE');

                      $query3= Production::select('production.Quantity as issueQTY','production.Raw_Material','prj_project.pname','prj_subproject.spname','prj_organisation.organisation','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','production.Production_Date')
                        ->where('production.Raw_Material', $id)
                        ->leftJoin('prj_organisation','production.Organization_Name','=','prj_organisation.id')
                        ->leftJoin('prj_project','production.Unit_Name','=','prj_project.id')
                        ->leftJoin('prj_subproject','production.Plant_Name','=','prj_subproject.id')
                            ->leftJoin('materialmanagement_add_material','production.Raw_Material','=','materialmanagement_add_material.id')
                            ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                        ->where('production.Approve_status', 'APPROVE');


                    $query = ProductionData::select('production_data.*','prj_material.material_name as matname','materialmanagement_add_material.UOM','materialmanagement_add_material.last_purchase_price','prj_organisation.organisation','prj_project.pname','prj_subproject.spname')
                    ->leftJoin('production', 'production_data.productionID', '=', 'production.id')
                    ->leftJoin('materialmanagement_add_material','production_data.RawMaterial_id','=','materialmanagement_add_material.id')
                    ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                    ->leftJoin('prj_organisation','production.Organization_Name','=','prj_organisation.id')
                    ->leftJoin('prj_project','production.Unit_Name','=','prj_project.id')
                    ->leftJoin('prj_subproject','production.Plant_Name','=','prj_subproject.id')
                    ->where('production_data.RawMaterial_id',$id)
                    ->where('production.Approve_status', 'APPROVE');

                }

                if ($fromdate && $todate) {
                    $query2->where('store_issue_approved_material.created_at', '>=', $fromdate)
                        ->where('store_issue_approved_material.created_at', '<', $todate);
                    $query3->where('production.Production_Date', '>=', $fromdate)
                        ->where('production.Production_Date', '<', $todate);
                    $query->where('production_data.created_at', '>=', $fromdate)
                        ->where('production_data.created_at', '<', $todate);
                }
                
                if ($request->has('Organization') && $request->input('Organization') != '') {
                    $Organization = $request->input('Organization');
                    if ($Organization !== 'all') {
                        $query2->where('store_requistion.Organization_Name', $Organization);
                        $query3->where('production.Organization_Name', $Organization);
                    }
                }
                if ($request->has('Godown_Name') && $request->input('Godown_Name') != '') {
                    $Godown_Name = $request->input('Godown_Name');
                    if ($Godown_Name !== 'all') {
                        $query2->where('store_requistion.Godown_Name', $Godown_Name);
                    }
                }
                if ($request->has('Plant_Name') && $request->input('Plant_Name') != '') {
                    $plant = $request->input('Plant_Name');
                    if ($plant !== 'all') {
                        $query2->where('store_requistion.Plant_Name', $plant);
                        $query->where('production.Plant_Name', $plant);
                        $query3->where('production.Plant_Name', $plant);
                    }
                }
                
                if ($request->has('Manufacturing_Unit') && $request->input('Manufacturing_Unit') != '') {
                    $project = $request->input('Manufacturing_Unit');
                    if ($project !== 'all') {
                        $query2->where('store_requistion.Manufacturing_Unit', $project);
                        $query->where('production.Unit_Name', $project);
                        $query3->where('production.Unit_Name', $project);
                    }
                }

                
                if ($request->has('Material_Name') && $request->input('Material_Name') != '') {
                    $Materialss = $request->input('Material_Name');
                    if ($Materialss !== 'all') {
                        $query2->where('store_issue_approved_material.Material_id', $Materialss);
                        $query->where('production_data.RawMaterial_id', $Materialss);
                        $query3->where('production.Raw_Material', $Materialss);
                        
                    }
                }

                $Receivedmaterials = $query2->get();
                $Receivedmaterials = $Receivedmaterials->merge($query3->get());
                $Issuedmaterials = $query->get();
                //dd($Issuedmaterials);
        


        $Product = Factory_Product::all();
        $Organization = prj_organisation::all();
        $Godown_Name = Prj_Inventory::all();
        $Manufacturing_unit = prj_project::all();
        $plant_name = Prj_Subproject::all();
        $Sub_Product = Factory_Sub_Product::all();
        $Sub_Sub_Product = Factory_Sub_Sub_Product::all();
        $UOM = Factory_Uom::all();
        $BOM_DATA = BOM::where('Approve_status', 'APPROVE')->get();
        $Material_Name = MaterialManagement_Add_Material::select('materialmanagement_add_material.*','prj_material.material_name')
                ->leftJoin('prj_material','materialmanagement_add_material.Material_Name','=','prj_material.id')
                ->where('Approve_status', 'APPROVE')
                ->get();

        $Dropdown = Production_Process::orderBy('id', 'DESC')->get();

        return view('Report/plantstockreportdetails', ['Receivedmaterials' => $Receivedmaterials,'Issuedmaterials'=>$Issuedmaterials,'Product' => $Product, 'Sub_Product' => $Sub_Product, 'Sub_Sub_Product' => $Sub_Sub_Product, 'UOM' => $UOM, 'Material_Name' => $Material_Name, 'Materialss' => $Materialss,'Organization'=>$Organization,'Godown_Name'=>$Godown_Name,'Manufacturing_unit'=>$Manufacturing_unit,'plant_name'=>$plant_name,'fromdate' => $fromdate,'todate' => $dateto,'matid'=>$id]);
    }





    function next($id, $type)
    {
        $datra = Session::get('nexdata');
        if (isset($datra)) {
            $datra = $datra[$type];
            $key = array_search($id, $datra);
            if (isset($datra[$key + 1])) {
                return $datra[$key + 1] . '/' . $type;
            }
        }
        return '';
    }

    public function delete($id)
    {
        $data = Production_Process::find($id);

        if (!$data) {
            return back()->with('error', 'Record not found');
        }

        $stages = Production_Process_Stage::where('Production_Process_Id', $data->id)->get();

        foreach ($stages as $stage) {
            $stageData = Production_Process_Stage_Data::where('Production_Process_Stage_Id', $stage->id)->get();

            foreach ($stageData as $dataItem) {
                Production_Process_Machine::where('Production_Process_Stage_Data_Id', $dataItem->id)->delete();
            }

            $stage->delete();
        }

        $data->delete();

        return back()->with('success', 'Deleted Successfully...');
    }


    public function CheckBoxStore(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('id');
        $columns = $request->input('columns');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        if ($data->count() > 0) {
            $data->each(function ($item) {
                $item->delete();
            });
        }

        if (isset($columns) && $columns != '') {
            foreach (explode(',', $columns) as $key => $value) {
                $insert = new CheckBox;
                $insert->userID = $userID;
                $insert->tableID = $id;
                $insert->CheckBox = $value;
                $insert->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Inserted']);
    }

    public function getCheckBoxData(Request $request)
    {
        $userID = auth()->user()->id;
        $id = $request->input('ID');

        $data = CheckBox::where('userID', $userID)->where('tableID', $id)->get();

        return response()->json(['success' => true, 'columns' => $data->pluck('CheckBox')]);
    }
    public function exportdata(Request $request)
    {
        $Materials = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->where('Approve_status', 'APPROVE')->get();
        $Material_arr = [];

        foreach ($Materials as $val) {
            $lppamountQuery = Master_Raw_Material::where('Material', $val->id);
            $total_received_qtyQuery = Master_Raw_Material_Detail::where('Material', $val->id);
            $total_received_amtQuery = Master_Raw_Material_Detail::where('Material', $val->id);
            $total_issue_qtyQuery = StoreIssueApprovedMaterial::select('store_issue_approved_material.*', 'store_requistion.Organization_Name', 'store_requistion.Manufacturing_Unit', 'store_requistion.Plant_Name')
                ->leftJoin('store_requistion', 'store_issue_approved_material.Store_Requistion_id', '=', 'store_requistion.id')
                ->where('store_issue_approved_material.Material_id', $val->id)
                ->where('action', 'APPROVE');
            $closing_balanceQuery = Master_Raw_Material::where('Material', $val->id);
            $closing_bal_amountQuery = Master_Raw_Material::where('Material', $val->id);
            
            $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
            
            $total_received_qty = $total_received_qtyQuery->sum('Quantity'); // Assuming 'received_qty' is the correct column name
            $total_received_amt = $total_received_amtQuery->sum('Amount'); // Assuming 'received_amt' is the correct column name
            $total_issue_qty = $total_issue_qtyQuery->sum('issueQTY');
            $closing_balance = $closing_balanceQuery->sum('Quantity'); // Assuming 'closing_qty' is the correct column name
            $closing_bal_amount = $closing_bal_amountQuery->sum('Amount'); // Assuming 'closing_amt' is the correct column name
            
            $lpp_arr[$val->id] = $lppamount ? $lppamount->Rate : 'N/A';
            $total_qty_arr[$val->id] = $total_received_qty !== null ? $total_received_qty : 'N/A';
            $total_mat_amt_arr[$val->id] = $total_received_amt !== null ? $total_received_amt : 'N/A';
            $total_iss_qty_arr[$val->id] = $total_issue_qty !== null ? $total_issue_qty : 'N/A';
            $closing_qty_arr[$val->id] = $closing_balance !== null ? $closing_balance : 'N/A';
            $closing_amt_arr[$val->id] = $closing_bal_amount !== null ? $closing_bal_amount : 'N/A';
            
            if ($lppamount) {
                $issueprice = $total_issue_qty * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $issueprice = 'N/A';
            }
            $val->issueprice = $issueprice;
            
            $Material_arr[] = $val;
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 13)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Material_arr as $key => $val) {
            $rowData = [
                "Sl No." => $key + 1,
                "Material Name" => isset($val->matname) && $val->matname != '' ? $val->matname : '',
                "UOM" => isset($val->UOM) && $val->UOM != '' ? $val->UOM : '',
                "LPP" => $lpp_arr[$val->id] ?? 'N/A',
                "Total Receive Qty." => $total_qty_arr[$val->id] ?? 'N/A',
                "Total Receive Amt." => $total_mat_amt_arr[$val->id] ?? 'N/A',
                "Total Issue Qty." => $total_iss_qty_arr[$val->id] ?? 'N/A',
                "Total Issued Amt." => $val->issueprice ?? 'N/A',
                "Closing Balance" => $closing_qty_arr[$val->id] ?? 'N/A',
                "Closing Amt." => $closing_amt_arr[$val->id] ?? 'N/A',
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $field => $value) {
                    if (in_array($field, $Checkbox_Arr)) {
                        $filteredRow[$field] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
        }

        $file = "store_stock_report_data.csv";
        $this->collectionExport($d, $file);
    }

    public function collectionExport($d, $file)
    {
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $file);

        $fp = fopen('php://output', 'w');
        $header = null;
        foreach ($d as $k => $row1) {

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

    

    public function exportdata_plant(Request $request)
    {
        $Materials = MaterialManagement_Add_Material::select('materialmanagement_add_material.*', 'prj_material.material_name as matname')
            ->leftJoin('prj_material', 'materialmanagement_add_material.Material_Name', '=', 'prj_material.id')
            ->where('Approve_status', 'APPROVE')->get();
        $Material_arr = [];

        foreach ($Materials as $val) {
            $lppamountQuery = Master_Raw_Material::where('Material', $val->id);
            $total_received_qtyQuery = StoreIssueApprovedMaterial::select('store_issue_approved_material.*', 'store_requistion.Organization_Name', 'store_requistion.Manufacturing_Unit', 'store_requistion.Plant_Name')
            ->leftJoin('store_requistion', 'store_issue_approved_material.Store_Requistion_id', '=', 'store_requistion.id')
            ->where('store_issue_approved_material.Material_id', $val->id)
            ->where('action', 'APPROVE');
            //$total_received_amtQuery = Master_Raw_Material_Detail::where('Material', $val->id);
            $total_issue_qtyQuery = ProductionData::select('production_data.*', 'production.Organization_Name', 'production.Unit_Name', 'production.Plant_Name')
                ->leftJoin('production', 'production_data.productionID', '=', 'production.id')
                ->where('production_data.RawMaterial_id', $val->id);
            $closing_balanceQuery = PlantStock::where('materialID', $val->id)->where('type', 0);
            //$closing_bal_amountQuery = Master_Raw_Material::where('Material', $val->id);
            
            $lppamount = $lppamountQuery->orderBy('id', 'DESC')->first();
            
            $total_received_qty = $total_received_qtyQuery->sum('issueQTY'); // Assuming 'received_qty' is the correct column name
            //$total_received_amt = $total_received_amtQuery->sum('Amount'); // Assuming 'received_amt' is the correct column name
            $total_issue_qty = $total_issue_qtyQuery->sum('TotalQty');
            $closing_balance = $closing_balanceQuery->sum('stock'); // Assuming 'closing_qty' is the correct column name
            //$closing_bal_amount = $closing_bal_amountQuery->sum('Amount'); // Assuming 'closing_amt' is the correct column name
            
            $lpp_arr[$val->id] = $lppamount ? $lppamount->Rate : 'N/A';
            $total_qty_arr[$val->id] = $total_received_qty !== null ? $total_received_qty : 'N/A';
            //$total_mat_amt_arr[$val->id] = $total_received_amt !== null ? $total_received_amt : 'N/A';
            $total_iss_qty_arr[$val->id] = $total_issue_qty !== null ? $total_issue_qty : 'N/A';
            $closing_qty_arr[$val->id] = $closing_balance !== null ? $closing_balance : 'N/A';
            //$closing_amt_arr[$val->id] = $closing_bal_amount !== null ? $closing_bal_amount : 'N/A';
            
            if ($lppamount && is_numeric($total_received_qty)) {
                $receiveprice = $total_received_qty * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $receiveprice = null;
            }
            $val->receiveprice = $receiveprice;
        
            if ($lppamount && is_numeric($total_issue_qty)) {
                $issueprice = $total_issue_qty * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $issueprice = null;
            }
            $val->issueprice = $issueprice;
        
            if ($lppamount && is_numeric($closing_balance)) {
                $closingprice = $closing_balance * ($lppamount->Rate + $lppamount->GST / 100);
            } else {
                $closingprice = null;
            }
            $val->closingprice = $closingprice;
            
            $Material_arr[] = $val;
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 13)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($Material_arr as $key => $val) {
            $rowData = [
                "Sl No." => $key + 1,
                "Material Name" => isset($val->matname) && $val->matname != '' ? $val->matname : '',
                "UOM" => isset($val->UOM) && $val->UOM != '' ? $val->UOM : '',
                "LPP" => $lpp_arr[$val->id] ?? 'N/A',
                "Total Receive Qty." => $total_qty_arr[$val->id] ?? 'N/A',
                "Total Receive Amt." => $val->receiveprice ?? 'N/A',
                "Total Issue Qty." => $total_iss_qty_arr[$val->id] ?? 'N/A',
                "Total Issued Amt." => $val->issueprice ?? 'N/A',
                "Closing Balance" => $closing_qty_arr[$val->id] ?? 'N/A',
                "Closing Amt." => $val->closingprice ?? 'N/A',
            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $field => $value) {
                    if (in_array($field, $Checkbox_Arr)) {
                        $filteredRow[$field] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
        }

        $file = "plant_stock_report_data.csv";
        $this->collectionExport($d, $file);
    }
}
