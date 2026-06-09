<?php

namespace App\Http\Controllers\FactoryCreater;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{City, Country, State, Admin, Department_Assign, Employee_Department, Forwarded_Data};
use App\Models\FactoryCreater\{Factory_Organisation,prj_organisation,unitname,gst, Factory_Name_Of_Unit, Factory_Address_Detail, Factory_Address, Factory_Statutory_Detail, Factory_Statutory_Detail_Other, Factory_Land_Building, Factory_Land_Building_Other, Factory_Land_Building_Boundary_Type, factory_land_building_attachement, Factory_Plant_Machinery, Factory_Plant_Machineries_Machine_Name, Factory_Plant_Machineries_Warranty, Factory_Plant_Machineries_Other, Factory_Uom, Factory_Amenitie, Factory_Amenities_Other, Factory_Electricity, Factory_Electricities_Generator, Factory_Warehouse_Room, Factory_Warehouse_Rooms_Room_Name, Factory_Warehouse_Rooms_Warehouse_Name, Factory_Office_Asset, Factory_Office_Assets_Type, Factory_Store, Factory_Stores_Sub_Rack_No, Factory_Stores_Sub_Rack_No_Bin_No, Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No, Factory_Shelf_Details, Factory_Shelf_Details_Shelf_No, Factory_Shelf_Details_Shelf_No_Sub_Shelf_No, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Approve};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Asset_Details, Master_Statutory_Factory_License_No, Master_Statutory_GST, Master_Statutory_Labour_License_No, Master_Statutory_PAN, Master_Statutory_Polution_Certificate_No};
use App\Models\Master\landbuilding\{Master_Land_Building, Master_Boundary_Height, Master_Boundary_Type, Master_Boundary_Width, Master_Building_Area, Master_Building_Type, Master_Cover_Area, Master_Gate, Master_Land_Area, Master_Open_Area, Master_Window};
use App\Models\Master\Plant\{Master_Accessories, Master_Duration, Master_Machine_Code, Master_Machine_Name, Master_Make_Model, Master_Production_Capacity, Master_Specification, Master_Warranty};

use Session;


class FactoryApproveController extends Controller
{
    public function factory_approve(Request $request)
    {
        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));

        $EXT = Session::get('EXT');
        $STEP = Session::get('STEP');

        $query = new Factory_Address_Detail;

        if ($fromdate && $todate) {
            $query = $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        if (isset($EXT[1]['Forward']) && isset($EXT[1]['approver'])) {
            $query = $query->where(function ($query) use ($EXT) {
                $query->where('Approve_status', null)->where('Forward_Status', 0)->whereRaw("Approve_Step IN (" . implode(",", $EXT[1]['approver']) . ")");
            })
                ->orWhere(function ($query) {
                    $query->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1');
                })
                ->orWhereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD") AND `Forward_Status` = 1')
                ->orderBy('id', 'DESC');
        } elseif (isset($EXT[1]['Forward'])) {
            $query = $query->where('Forward_Status', 1)->whereRaw('id IN (SELECT DataID FROM forwarded_data WHERE Forward_To_id="' . auth()->user()->id . '" AND status=0) AND (Approve_status IS NULL OR Approve_status="FORWARD")')->orderBy('id', 'DESC');
        } elseif (isset($EXT[1]['approver'])) {
            $query = $query->where('Approve_status', null)->where('Forward_Status', 0)->WhereRaw("Approve_Step IN (" . implode(",", $EXT[1]['approver']) . ")")->orderBy('id', 'DESC');
        }

        $addressDetails = $query->get();

        $addressDetails_arr = array();
        foreach ($addressDetails as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="1" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=1 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->statess = State::find($val->state);
            $val->districtss = City::find($val->district);
            $val->gst = Master_Statutory_GST::find($val->GST);
            $val->factoryLicense = Master_Statutory_Factory_License_No::find($val->Factory_License_No);
            $val->org = prj_organisation::where('id', $val->organization)->first();
            $val->unitname = unitname::where('id', $val->name_of_unit)->first();
            $val->plantsCount = Factory_Plant_Machinery::where('factory_id', $val->id)->count();
            $val->statutory = Factory_Statutory_Detail::where('factory_id', $val->id)->first();
            if (!empty($val->statutory)) {
                //$val->gst = Master_Statutory_GST::find($val->statutory->gst_no);
                $val->gst = gst::find($val->statutory->gst_no);
            }
            if (!empty($val->statutory)) {
                $val->factoryLicense = Master_Statutory_Factory_License_No::find($val->statutory->factory_license_no);
            }
            $val->landbuilding = Factory_Land_Building::where('factory_id', $val->id)->first();
            if (!empty($val->landbuilding)) {
                $val->landtype = Master_Land_Building::find($val->landbuilding->land_type);
            }
            if (!empty($val->landbuilding)) {
                $val->landarea = Master_Land_Area::find($val->landbuilding->land_area);
            }
            $val->store = Factory_Store::where('factory_id', $val->id)->first();
            $val->WareHouseRoom = Factory_Warehouse_Room::where('factory_id', $val->id)->first();
            $count = Factory_Store::where('factory_id', $val->id)->count();
            if ($count > 0) {
                array_push($addressDetails_arr, $val);
            }
        }

        return view('FectoryCreater.ApproveTable', ['addressDetails' => $addressDetails_arr, 'fromdate' => $fromdate, 'todate' => $dateto]);
    }

    public function view_approve(Request $request, $id, $type)
    {
        $approvestatus = Factory_Approve::where('factory_id', $id)->where('status', '1')->first();

        $appro = Factory_Approve::where('factory_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        $Organization = prj_organisation::all();
        $nameOfUnit = unitname::all();
        $city = City::all();
        $country = Country::all();
        $state = State::all();
        $uom = Factory_Uom::all();
        $masterplant = Prj_Subproject::all();
        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subsubproduct = Factory_Sub_Sub_Product::all();
        $ProductionCapacity = Master_Production_Capacity::all();
        $Duration = Master_Duration::all();
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $Accessories = Master_Accessories::all();
        $Specification = Master_Specification::all();
        $Make_Model = Master_Make_Model::all();
        $Warranty = Master_Warranty::all();
        $masterland = Master_Land_Building::all();
        $boundaryheight = Master_Boundary_Height::all();
        $boundarytype = Master_Boundary_Type::all();
        $boundarywidth = Master_Boundary_Width::all();
        $buildingarea = Master_Building_Area::all();
        $buildingtype = Master_Building_Type::all();
        $coverarea = Master_Cover_Area::all();
        $gate = Master_Gate::all();
        $landarea = Master_Land_Area::all();
        $openarea = Master_Open_Area::all();
        $window = Master_Window::all();
        $gst = gst::all();
        $assetdeatils = Asset_Details::all();
        $pan = Master_Statutory_PAN::all();
        $factoryLicense = Master_Statutory_Factory_License_No::all();
        $labourLicense = Master_Statutory_Labour_License_No::all();
        $polution = Master_Statutory_Polution_Certificate_No::all();
        $employeeName = Admin::where('role', 1)->whereRaw('id IN (SELECT userID FROM employee_department where Departments="1")')->get();


        $addresssdetails = Factory_Address_Detail::where('id', $id)->first();
        $address = Factory_Address::where('factory_id', $id)->get();

        $statury = Factory_Statutory_Detail::where('factory_id', $id)->first();
        $stuother = array();
        if (isset($statury->id)) {
            $stuother = Factory_Statutory_Detail_Other::where('factory_statutory_details_id', $statury->id)->get();
        }
        $landbulding = Factory_Land_Building::where('factory_id', $id)->first();
        $landtype = array();
        $landattch = array();
        $landother = array();
        if (isset($landbulding->id)) {
            $landtype = Factory_Land_Building_Boundary_Type::where('factory_land_building_id', $landbulding->id)->get();
            $landattch = factory_land_building_attachement::where('factory_land_building_id', $landbulding->id)->get();
            $landother = Factory_Land_Building_Other::where('factory_land_building_id', $landbulding->id)->get();
        }
        $plantmach = Factory_Plant_Machinery::select('factory_plant_machineries.*','factory_uoms.UOMs')
                    ->leftJoin('factory_uoms','factory_plant_machineries.uom_prd','=','factory_uoms.id')
                    ->where('factory_id', $id)->get();
        $plantmach_arr = array();
        foreach ($plantmach as $val) {
            $val->machinename = Factory_Plant_Machineries_Machine_Name::where('factory_plant_machineries_id', $val->id)->get();
            $val->warrnty = Factory_Plant_Machineries_Warranty::where('factory_plant_machineries_id', $val->id)->get();
            $val->other = Factory_Plant_Machineries_Other::where('factory_plant_machineries_id', $val->id)->get();

            array_push($plantmach_arr, $val);
        }

        $Amenitiess = Factory_Amenitie::where('factory_id', $id)->first();
        $amentOther = array();
        if (isset($Amenitiess->id)) {
            $amentOther = Factory_Amenities_Other::where('factory_amenities_id', $Amenitiess->id)->get();
        }

        $Electri = Factory_Electricity::where('factory_id', $id)->get();
        $Electrigenrate = Factory_Electricities_Generator::where('factory_id', $id)->get();

        $warehousetotal = Factory_Warehouse_Room::where('factory_id', $id)->first();
        $warehouseroom = array();
        $warehouse = array();
        if (isset($warehousetotal->id)) {
            $warehouseroom = Factory_Warehouse_Rooms_Room_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->get();
            $warehouse = Factory_Warehouse_Rooms_Warehouse_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->get();
        }

        $officeasst = Factory_Office_Asset::where('factory_id', $id)->first();
        $assettypee = array();
        if (isset($officeasst->id)) {
            $assettypee = Factory_Office_Assets_Type::where('factory_office_assets_id', $officeasst->id)->get();
        }

        $storee = Factory_Store::where('factory_id', $id)->first();
        $storesubrack = array();
        if (isset($storee->id)) {
            $storesubrack = Factory_Stores_Sub_Rack_No::where('factory_stores_id', $storee->id)->get();
        }
        $storesubrack_arr = array();
        foreach ($storesubrack as $val) {
            $val->storebin = Factory_Stores_Sub_Rack_No_Bin_No::where('factory_stores_sub_rack_no_id', $val->id)->get();
            $arrayyy = array();
            foreach ($val->storebin as $vall) {
                $vall->storesubbin = Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No::where('factory_stores_sub_rack_no_bin_no_id', $vall->id)->get();
                array_push($arrayyy, $vall);
            }

            $val->sturesubbinss = $arrayyy;
            array_push($storesubrack_arr, $val);
        }


        $shelf = Factory_Shelf_Details::where('factory_id', $id)->first();
        $shelfno_arr = array();
        if (isset($shelf->id)) {
            $shelfno = Factory_Shelf_Details_Shelf_No::where('factory_shelf_details_id', $shelf->id)->get();
            $shelfno_arr = array();
            foreach ($shelfno as $val) {
                $val->subshelfsss = Factory_Shelf_Details_Shelf_No_Sub_Shelf_No::where('factory_shelf_details_shelf_no_id', $val->id)->get();

                array_push($shelfno_arr, $val);
            }
        }

        $nextID = $this->next($id, $type);

        return view('FectoryCreater.ViewApprove', ['Organization' => $Organization, 'nameOfUnit' => $nameOfUnit, 'city' => $city, 'country' => $country, 'state' => $state, 'uom' => $uom, 'masterplant' => $masterplant, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'showdata' => $addresssdetails, 'address' => $address, 'statury' => $statury, 'stuother' => $stuother, 'landbulding' => $landbulding, 'landtype' => $landtype, 'landattch' => $landattch, 'landother' => $landother, 'plantmach' => $plantmach_arr, 'Amenitiess' => $Amenitiess, 'amentOther' => $amentOther, 'Electri' => $Electri, 'Electrigenrate' => $Electrigenrate, 'warehousetotal' => $warehousetotal, 'warehouse' => $warehouse, 'warehouseroom' => $warehouseroom, 'officeasst' => $officeasst, 'assettypee' => $assettypee, 'storee' => $storee, 'storesubrack' => $storesubrack_arr, 'shelf' => $shelf, 'shelfno' => $shelfno_arr, 'ProductionCapacity' => $ProductionCapacity, 'Duration' => $Duration, 'MachineName' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Accessories' => $Accessories, 'Specification' => $Specification, 'Make_Model' => $Make_Model, 'Warranty' => $Warranty, 'masterland' => $masterland, 'boundaryheight' => $boundaryheight, 'boundarytype' => $boundarytype, 'boundarywidth' => $boundarywidth, 'buildingarea' => $buildingarea, 'buildingtype' => $buildingtype, 'coverarea' => $coverarea, 'gate' => $gate, 'landarea' => $landarea, 'openarea' => $openarea, 'window' => $window, 'gst' => $gst, 'pan' => $pan, 'factoryLicense' => $factoryLicense, 'labourLicense' => $labourLicense, 'polution' => $polution, 'approvestatus' => $approvestatus, 'approves' => $approves, 'nextID' => $nextID, 'employeeName' => $employeeName,'assetdeatils' => $assetdeatils]);
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

    public function approve(Request $request)
    {
        //return $request->all();
        $EXT = Session::get('EXT');

        if (!empty($request->during_approval)) {
            Factory_Address_Detail::where('id', $request->approveID)->update(['Approve_status' => $request->during_approval]);
            Factory_Approve::where('factory_id', $request->approveID)->where('status', 1)->update(['status' => 0]);
        }

        $check = Factory_Address_Detail::find($request->approveID);
        if ($request->during_approval === 'APPROVE') {
            $status = Forwarded_Data::where('DataID', $request->approveID)->update(['status' => 1]);
            Factory_Address_Detail::where('id', $request->approveID)->update(['Forward_Status' => 0]);

            $DepartStepcount2 = Department_Assign::where(['departments' => 1, 'step' => 2])->count();
            $DepartStepcount3 = Department_Assign::where(['departments' => 1, 'step' => 3])->count();

            if ($check->Approve_Step == 1 && $DepartStepcount2 > 0) {
                Factory_Address_Detail::where('id', $request->approveID)->update(['Approve_Step' => 2, 'Approve_status' => null]);
            }
            if ($check->Approve_Step == 2 &&  $DepartStepcount3 > 0) {
                Factory_Address_Detail::where('id', $request->approveID)->update(['Approve_Step' => 3, 'Approve_status' => null]);
            }
        }
        $checkprjid=Factory_Address_Detail::find($request->approveID);

        // if($checkprjid->Approve_status=== 'APPROVE'){
        //     $approvetable = new FactoryProjectApproval;
        //     $approvetable->approve_by = auth()->user()->id;
        //     $approvetable->Approval_status = 'APPROVE';
        //     $approvetable->pid = $check->name_of_unit;
        //     $approvetable->approval_id = $check->id;
        //     $approvetable->save();
        // }

        if ($request->during_approval === 'FORWARD') {
            Forwarded_Data::where(['DepartmentID' => 1, 'DataID' => $request->approveID])->update(['status' => 1]);
            Factory_Address_Detail::where('id', $request->approveID)->update(['Forward_Status' => 1]);

            $forward = new Forwarded_Data;
            $forward->userID = auth()->user()->id;
            $forward->Forward_To_id = $request->Forward_To;
            $forward->DepartmentID = 1;
            $forward->DataID = $request->approveID;
            $forward->status = 0;

            $forward->save();
        }

        $approve = new Factory_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif ($check->Approve_status == 'OBJECT') {
            $approve->role = 'Inputer';
        } elseif (isset($EXT[1]['approver'])) {
            $approve->role = 'Approver';
        } else {
            $approve->role = 'Viewer';
        }
        $approve->factory_id = $request->approveID;
        $approve->status = 1;
        if ($request->during_approval != '') {
            $approve->action = $request->during_approval;
        } elseif ($request->pre_post_approval != '') {
            $approve->pre_post_approval = $request->pre_post_approval;
        } else {
            $approve->action = 'Replied';
        }
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->days_for_holding = $request->days_for_holding;
        $approve->Forward_To = $request->Forward_To;

        $approve->save();

        if ($request->during_approval == '' && $request->pre_post_approval == '') {
            Factory_Address_Detail::where('id', $request->approveID)->update(['Approve_status' => null]);
            
            return redirect('FactoryCreater/List')->with('success', 'successfully.....');
        } elseif (($request->pre_post_approval == 'AUDIT' || $request->pre_post_approval == 'INTIMATION' || $request->pre_post_approval == 'QUERY') && $request->non_acting == 1) {
            return redirect('FactoryCreater/List')->with('success', 'successfull.....');
        } else {
            return redirect('FactoryCreater/factory-approve')->with('success', 'Approved successfully.....');
        }
    }


    public function CheckHoldExpiry()
    {
        $Approve = Factory_Approve::all();

        return response()->json($Approve);
    }

    public function UpdateStatus(Request $request)
    {
        $factory_id = $request->input('factory_id');
        $userID = $request->input('userID');

        $approve = Factory_Approve::where('factory_id', $factory_id)->where('userID', $userID)->update(['status' => 0]);
        $factory =  Factory_Address_Detail::where('id', $factory_id)->update(['Approve_status' => null]);

        $approve = new Factory_Approve;
        $approve->role = 'AUTO';
        $approve->factory_id = $factory_id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        $response = array(
            'success' => true,
            'message' => 'Updated successfully.'
        );

        return response()->json($response);
    }


    public function Release_Hold(Request $request, $id)
    {
        $EXT = Session::get('EXT');
        $currentDate = now();

        $approvesss = Factory_Approve::where('factory_id', $id)->where('action', 'HOLD')->update(['days_for_holding' => $currentDate, 'status' => 0]);
        $factory =  Factory_Address_Detail::where('id', $id)->update(['Approve_status' => null]);

        $approve = new Factory_Approve;
        $approve->userID = auth()->user()->id;
        if (auth()->user()->role == 0) {
            $approve->role = 'Admin';
        } elseif (isset($EXT[1]['approver'])) {
            $approve->role = 'Approver';
        } elseif (isset($EXT[1]['inputer'])) {
            $approve->role = 'Inputer';
        } else {
            $approve->role = 'Viewer';
        }

        $approve->factory_id = $id;
        $approve->status = 1;
        $approve->action = 'Hold Released';
        $approve->comment_text = $request->comment_text;
        $approve->ip_address = $request->getClientIp();
        $approve->device_name = $request->server('HTTP_USER_AGENT');
        $approve->save();

        return redirect('FactoryCreater/List')->with('success', 'Hold Released successfully.....');
    }
}
