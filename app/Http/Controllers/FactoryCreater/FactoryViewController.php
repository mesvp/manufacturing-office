<?php

namespace App\Http\Controllers\FactoryCreater;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Admin, City, Country, State, CheckBox, Department_Assign, Forwarded_Data};
use App\Models\FactoryCreater\{Factory_Organisation,prj_organisation,unitname,gst, Factory_Name_Of_Unit, Factory_Address_Detail, Factory_Address, Factory_Statutory_Detail, Factory_Statutory_Detail_Other, Factory_Land_Building, Factory_Land_Building_Other, Factory_Land_Building_Boundary_Type, factory_land_building_attachement, Factory_Plant_Machinery, Factory_Plant_Machineries_Machine_Name, Factory_Plant_Machineries_Warranty, Factory_Plant_Machineries_Other, Factory_Uom, Factory_Amenitie, Factory_Amenities_Other, Factory_Electricity, Factory_Electricities_Generator, Factory_Warehouse_Room, Factory_Warehouse_Rooms_Room_Name, Factory_Warehouse_Rooms_Warehouse_Name, Factory_Office_Asset, Factory_Office_Assets_Type, Factory_Store, Factory_Stores_Sub_Rack_No, Factory_Stores_Sub_Rack_No_Bin_No, Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No, Factory_Shelf_Details, Factory_Shelf_Details_Shelf_No, Factory_Shelf_Details_Shelf_No_Sub_Shelf_No, Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Approve};
use App\Models\Master\{Master_Plant_Machinery,Prj_Subproject,Asset_Details, Master_Statutory_Factory_License_No, Master_Statutory_GST, Master_Statutory_Labour_License_No, Master_Statutory_PAN,Master_Statutory_Polution_Certificate_No,Master_Material_data,Module_Bsns_Unit,Module_Prj_Assignment};
use App\Models\Master\landbuilding\{Master_Land_Building, Master_Boundary_Height, Master_Boundary_Type, Master_Boundary_Width, Master_Building_Area, Master_Building_Type, Master_Cover_Area, Master_Gate, Master_Land_Area, Master_Open_Area, Master_Window};
use App\Models\Master\Plant\{Master_Accessories, Master_Duration, Master_Machine_Code, Master_Machine_Name, Master_Make_Model, Master_Production_Capacity, Master_Specification, Master_Warranty};

use Session;


class FactoryViewController extends Controller
{
    public function Factory_Data(Request $request)
    {
        $factory_id = Session::get('factory_id');
        $Department = Session::get('Department');
        $EXT = Session::get('EXT');
        $STEP = Session::get('STEP');

        $dateto = $request->input('to_date');
        $fromdate = $request->input('from_date');
        $todate = date('Y-m-d', strtotime('+1 day', strtotime($request->input('to_date'))));
        if (isset($EXT[1]['inputer'])) {
            $query = Factory_Address_Detail::orderBy('id', 'DESC');
        } else {
            $query = Factory_Address_Detail::whereRaw('id IN (SELECT factory_id FROM factory_stores WHERE status = 0)')->orderBy('id', 'DESC');
        }

        if (!empty($fromdate) && !empty($todate)) {
            $query->whereBetween('created_at', [$fromdate, $todate]);
        }

        $Organizations = '';
        if ($request->has('Organization') && $request->input('Organization') != '') {
            $Organizations = $request->input('Organization');
            if ($Organizations !== 'all') {
                $query->where('organization', $Organizations);
            }
        }

        $nameOFUnit = '';
        if ($request->has('name_of_unit') && $request->input('name_of_unit') != '') {
            $nameOFUnit = $request->input('name_of_unit');
            if ($nameOFUnit !== 'all') {
                $query->where('name_of_unit', $nameOFUnit);
            }
        }

        $district = '';
        if ($request->has('District_State') && $request->input('District_State') != '') {
            $district = $request->input('District_State');
            if ($district !== 'all') {
                $query->where('district', $district);
            }
        }

        $plantsCount = '';
        if ($request->has('Total_Plant') && $request->input('Total_Plant') != '') {
            $plantsCount = $request->input('Total_Plant');
            if ($plantsCount !== 'all') {
                $query->whereHas('plantsCount', function ($subquery) use ($plantsCount) {
                    $subquery->havingRaw('COUNT(*) = ?', [$plantsCount]);
                });
            }
        }

        $selectedGST = '';
        if ($request->has('GST_No') && $request->input('GST_No') != '') {
            $selectedGST = $request->input('GST_No');
            if ($selectedGST !== 'all') {
               
                    $query->whereHas('statutory', function ($subquery) use ($selectedGST) {
                    $subquery->where('gst_no', $selectedGST);

                });
            }
        }

        $FactoryLicenses = '';
        if ($request->has('Factory_License') && $request->input('Factory_License') != '') {
            $FactoryLicenses = $request->input('Factory_License');
            if ($FactoryLicenses !== 'all') {
                $query->whereHas('statutory', function ($subquery) use ($FactoryLicenses) {
                    $subquery->where('factory_license_no', $FactoryLicenses);
                });
            }
        }

        $LandTypes = '';
        if ($request->has('Land_Type') && $request->input('Land_Type') != '') {
            $LandTypes = $request->input('Land_Type');
            if ($LandTypes !== 'all') {
                $query->whereHas('landbuilding', function ($subquery) use ($LandTypes) {
                    $subquery->where('land_type', $LandTypes);
                });
            }
        }

        $TotalGodowns = '';
        if ($request->has('Total_Godown') && $request->input('Total_Godown') != '') {
            $TotalGodowns = $request->input('Total_Godown');
            if ($TotalGodowns !== 'all') {
                $query->whereHas('WareHouseRoom', function ($subquery) use ($TotalGodowns) {
                    $subquery->where('Total_Warehouse', $TotalGodowns);
                });
            }
        }

        $TotalRacks = '';
        if ($request->has('Total_Rack') && $request->input('Total_Rack') != '') {
            $TotalRacks = $request->input('Total_Rack');
            if ($TotalRacks !== 'all') {
                $query->whereHas('store', function ($subquery) use ($TotalRacks) {
                    $subquery->where('Total_Rack', $TotalRacks);
                });
            }
        }

        $RackCapacitys = '';
        if ($request->has('Rack_Capacity') && $request->input('Rack_Capacity') != '') {
            $RackCapacitys = $request->input('Rack_Capacity');
            if ($RackCapacitys !== 'all') {
                $query->whereHas('store', function ($subquery) use ($RackCapacitys) {
                    $subquery->where('Rack_Capacity', $RackCapacitys);
                });
            }
        }

        $TotalBins = '';
        if ($request->has('Total_Bin') && $request->input('Total_Bin') != '') {
            $TotalBins = $request->input('Total_Bin');
            if ($TotalBins !== 'all') {
                $query->whereHas('store', function ($subquery) use ($TotalBins) {
                    $subquery->where('Total_Bin', $TotalBins);
                });
            }
        }

        $BinCaps = '';
        if ($request->has('Bin_Cap') && $request->input('Bin_Cap') != '') {
            $BinCaps = $request->input('Bin_Cap');
            if ($BinCaps !== 'all') {
                $query->whereHas('store', function ($subquery) use ($BinCaps) {
                    $subquery->where('Total_Bin_Capacity', $BinCaps);
                });
            }
        }


        $addressDetails = $query->get();

        $addressDetails_arr = [];
        $approved = [];
        $REJECT = [];
        $RECHECK = [];
        $OBJECT = [];
        $HOLD = [];
        $pending = [];
        foreach ($addressDetails as $val) {
            if ($val->Forward_Status != 1) {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT userID FROM `department_assign` WHERE departments="1" AND step="' . $val->Approve_Step . '")')->get();
            } else {
                $val->PendingWith = Admin::whereRaw('id IN(SELECT Forward_To_id FROM `forwarded_data` WHERE DataID="' . $val->id . '" AND DepartmentID=1 AND status=0)')->get();
            }
            $val->user = Admin::find($val->userID);
            $val->statess = State::find($val->state);
            $val->districtss = City::find($val->district);
            $val->org = prj_organisation::where('id', $val->organization)->first();
            $val->unitname = unitname::where('id', $val->name_of_unit)->first();
            $val->plantsCount = Factory_Plant_Machinery::where('factory_id', $val->id)->count();
            $val->statutory = Factory_Statutory_Detail::where('factory_id', $val->id)->first();
            if (!empty($val->statutory)) {
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
            $val->HoldStatus = Factory_Approve::where('factory_id', $val->id)->where('action', 'HOLD')->where('status', 1)->where('userID', auth()->user()->id)->count();

            $addressDetails_arr[] = $val;

            if ($val->Approve_status == 'APPROVE') {
                $approved[] = $val;
            } elseif ($val->Approve_status == 'REJECT') {
                $REJECT[] = $val;
            } elseif ($val->Approve_status == 'RECHECK') {
                $RECHECK[] = $val;
            } elseif ($val->Approve_status == 'OBJECT') {
                $OBJECT[] = $val;
            } elseif ($val->Approve_status == 'HOLD') {
                $HOLD[] = $val;
            } else {
                $pending[] = $val;
            }
        }

        $OrganizationData = prj_organisation::all();
        $nameOfUnitData = unitname::select('prj_project.*')
                        ->where('ptype','Corporate')
                        ->get();
        $gstData = gst::all();
        $factoryLicenseData = Master_Statutory_Factory_License_No::all();
        $landTypeData = Master_Land_Building::all();
        $cityData = City::all();
        $city_Arr = array();
        foreach ($cityData as $val) {
            $val->stateData = State::find($val->sid);
            array_push($city_Arr, $val);
        }

        $ForDropdown = Factory_Address_Detail::orderBy('id', 'DESC')->get();

        $ForDropdown_arr = array();
        foreach ($ForDropdown as $val) {
            $val->statess = State::find($val->state);
            $val->districtss = City::find($val->district);
            $val->org = Factory_Organisation::where('id', $val->organization)->first();
            $val->unitname = Factory_Name_Of_Unit::where('id', $val->name_of_unit)->first();
            $val->plantsCount = Factory_Plant_Machinery::where('factory_id', $val->id)->count();
            $val->statutory = Factory_Statutory_Detail::where('factory_id', $val->id)->first();
            if (!empty($val->statutory)) {
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

            array_push($ForDropdown_arr, $val);
        }

        return view('FectoryCreater.List_view', ['addressDetails' => $addressDetails_arr, 'approved' => $approved, 'REJECT' => $REJECT, 'RECHECK' => $RECHECK, 'OBJECT' => $OBJECT, 'HOLD' => $HOLD, 'pending' => $pending, 'DropdownData' => $ForDropdown_arr, 'fromdate' => $fromdate, 'todate' => $dateto, 'Organizations' => $Organizations, 'nameOFUnit' => $nameOFUnit, 'district' => $district, 'plantsCount' => $plantsCount, 'selectedGST' => $selectedGST, 'FactoryLicenses' => $FactoryLicenses, 'LandTypes' => $LandTypes, 'TotalGodowns' => $TotalGodowns, 'TotalRacks' => $TotalRacks, 'RackCapacitys' => $RackCapacitys, 'TotalBins' => $TotalBins, 'BinCaps' => $BinCaps, 'OrganizationData' => $OrganizationData, 'nameOfUnitData' => $nameOfUnitData, 'gstData' => $gstData, 'factoryLicenseData' => $factoryLicenseData, 'landTypeData' => $landTypeData, 'cityData' => $city_Arr]);
    }


    public function activeForm($id)
    {
        $step1 = Factory_Address_Detail::where('id', $id)->count();
        $form['step1'] = $step1 > 0 ? ' active ' : '';
        $step2 = Factory_Statutory_Detail::where('factory_id', $id)->count();
        $form['step2'] = $step2 > 0 ? ' active ' : ' disabled ';
        $step3 = Factory_Land_Building::where('factory_id', $id)->count();
        $form['step3'] = $step3 > 0 ? ' active ' : ' disabled ';
        $step4 = Factory_Plant_Machinery::where('factory_id', $id)->count();
        $form['step4'] = $step4 > 0 ? ' active ' : ' disabled ';
        $step5 = Factory_Amenitie::where('factory_id', $id)->count();
        $form['step5'] = $step5 > 0 ? ' active ' : ' disabled ';
        $step6 = Factory_Electricity::where('factory_id', $id)->count();
        $form['step6'] = $step6 > 0 ? ' active ' : ' disabled ';
        $step7 = Factory_Warehouse_Room::where('factory_id', $id)->count();
        $form['step7'] = $step7 > 0 ? ' active ' : ' disabled ';
        $step8 = Factory_Office_Asset::where('factory_id', $id)->count();
        $form['step8'] = $step8 > 0 ? ' active ' : ' disabled ';
        $step9 = Factory_Office_Asset::where('factory_id', $id)->count();
        $form['step9'] = $step9 > 0 ? ' active ' : ' disabled ';
        $step10 = Factory_Store::where('factory_id', $id)->count();
        $form['step10'] = $step10 > 0 ? ' active ' : ' disabled ';

        return $form;
    }

    public function Fectory_view($id, $type)
    {
        $appro = Factory_approve::where('factory_id', $id)->get();
        $approves = [];
        foreach ($appro as $val) {
            $val->user = Admin::find($val->userID);
            array_push($approves, $val);
        }

        //$Organization = Factory_Organisation::all();
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
        $storesubrack = Factory_Stores_Sub_Rack_No::where('factory_stores_id', $storee->id)->get();
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

        return view('FectoryCreater.View_page', ['Organization' => $Organization, 'nameOfUnit' => $nameOfUnit, 'city' => $city, 'country' => $country, 'state' => $state, 'uom' => $uom, 'masterplant' => $masterplant, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'showdata' => $addresssdetails, 'address' => $address, 'statury' => $statury, 'stuother' => $stuother, 'landbulding' => $landbulding, 'landtype' => $landtype, 'landattch' => $landattch, 'landother' => $landother, 'plantmach' => $plantmach_arr, 'Amenitiess' => $Amenitiess, 'amentOther' => $amentOther, 'Electri' => $Electri, 'Electrigenrate' => $Electrigenrate, 'warehousetotal' => $warehousetotal, 'warehouse' => $warehouse, 'warehouseroom' => $warehouseroom, 'officeasst' => $officeasst, 'assettypee' => $assettypee, 'storee' => $storee, 'storesubrack' => $storesubrack_arr, 'shelf' => $shelf, 'shelfno' => $shelfno_arr, 'ProductionCapacity' => $ProductionCapacity, 'Duration' => $Duration, 'MachineName' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Accessories' => $Accessories, 'Specification' => $Specification, 'Make_Model' => $Make_Model, 'Warranty' => $Warranty, 'masterland' => $masterland, 'boundaryheight' => $boundaryheight, 'boundarytype' => $boundarytype, 'boundarywidth' => $boundarywidth, 'buildingarea' => $buildingarea, 'buildingtype' => $buildingtype, 'coverarea' => $coverarea, 'gate' => $gate, 'landarea' => $landarea, 'openarea' => $openarea, 'window' => $window, 'gst' => $gst, 'pan' => $pan, 'factoryLicense' => $factoryLicense, 'labourLicense' => $labourLicense, 'polution' => $polution, 'approves' => $approves, 'nextID' => $nextID,'assetdeatils' => $assetdeatils]);
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

    public function unset()
    {
        session::forget('editID');
        Session::forget('factory_id');
        return redirect('FactoryCreater/step1');
    }

    public function step1($id = null)
    {
        if ($id != '' && $id != null) {
            Session::put('factory_id', $id);
        }

        $Organization = prj_organisation::all();
          $nameOfUnit = unitname::select('prj_project.*')
            ->where('ptype','Corporate')
            ->where('status','1')
            ->get();
        
        $city = City::all();
        $country = Country::select('countries.*')->Where('name','INDIA')->get();
        $state = State::select('prj_state.*')->where('status','1')->get();

        $editID = session::get('editID');
        $addresssdetails = array();
        $address = array();
        if (isset($id) && $id != '') {
            $addresssdetails = Factory_Address_Detail::where('id', $id)->first();
            $address = Factory_Address::where('factory_id', $id)->get();
            session::put('editID', $id);
        } elseif (Session::get('factory_id') != '') {
            $id = Session::get('factory_id');
            $addresssdetails = Factory_Address_Detail::where('id', $id)->first();
            $address = Factory_Address::where('factory_id', $id)->get();
            session::put('editID', $id);
        } elseif (isset($editID) && $editID != '') {
            $addresssdetails = Factory_Address_Detail::where('id', $editID)->first();
            $address = Factory_Address::where('factory_id', $editID)->get();
        }

        return view('FectoryCreater.Step1_addfactory', ['formdata' => $this->activeForm($id), 'Organization' => $Organization, 'nameOfUnit' => $nameOfUnit, 'city' => $city, 'country' => $country, 'state' => $state, 'addresssdetails' => $addresssdetails, 'address' => $address]);
    }

    public function step2($id = null)
    {

        $formActive = session::get('factory_id');
        $editID = session::get('editID');
        $getorgid=Factory_Address_Detail::select('factory_address_details.state','factory_address_details.organization')->where('id',$formActive)->first();
        // echo "<pre>";
        // print_r($getorgid);
        // echo "</pre>";
        $gst = gst::where('sid',$getorgid->state)->where('org_id',$getorgid->organization)->where('addrss_type','Billing Address')->get();
        $pan = Master_Statutory_PAN::all();
        $factoryLicense = Master_Statutory_Factory_License_No::all();
        $labourLicense = Master_Statutory_Labour_License_No::all();
        $polution = Master_Statutory_Polution_Certificate_No::all();

        $statury = Factory_Statutory_Detail::where('factory_id', $editID)->first();
        $stuother = array();
        $stuothercount = 0;
        if (isset($statury)) {
            $stuother = Factory_Statutory_Detail_Other::where('factory_statutory_details_id', $statury->id)->get();
            $stuothercount = Factory_Statutory_Detail_Other::where('factory_statutory_details_id', $statury->id)->count();
        }

        return view('FectoryCreater.Step2_addfactory', ['formdata' => $this->activeForm($formActive), 'statury' => $statury, 'stuother' => $stuother, 'stuothercount' => $stuothercount, 'gst' => $gst, 'pan' => $pan, 'factoryLicense' => $factoryLicense, 'labourLicense' => $labourLicense, 'polution' => $polution]);
    }


    public function step3($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');
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

        $landbulding = Factory_Land_Building::where('factory_id', $editID)->first();
        $landtype = array();
        $landattch = array();
        $landother = array();
        $landtypecount = 0;
        $landothercount = 0;
        if (isset($landbulding)) {
            $landtype = Factory_Land_Building_Boundary_Type::where('factory_land_building_id', $landbulding->id)->get();
            $landtypecount = Factory_Land_Building_Boundary_Type::where('factory_land_building_id', $landbulding->id)->count();
            $landattch = factory_land_building_attachement::where('factory_land_building_id', $landbulding->id)->get();
            $landother = Factory_Land_Building_Other::where('factory_land_building_id', $landbulding->id)->get();
            $landothercount = Factory_Land_Building_Other::where('factory_land_building_id', $landbulding->id)->count();
        }

        return view('FectoryCreater.Step3_addfactory', ['formdata' => $this->activeForm($formActive), 'landbulding' => $landbulding, 'landtype' => $landtype, 'landattch' => $landattch, 'landother' => $landother, 'landothercount' => $landothercount, 'landtypecount' => $landtypecount, 'masterland' => $masterland, 'boundaryheight' => $boundaryheight, 'boundarytype' => $boundarytype, 'boundarywidth' => $boundarywidth, 'buildingarea' => $buildingarea, 'buildingtype' => $buildingtype, 'coverarea' => $coverarea, 'gate' => $gate, 'landarea' => $landarea, 'openarea' => $openarea, 'window' => $window]);
    }

    public function step4($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');

        $masterplant_org=Factory_Address_Detail::select('factory_address_details.name_of_unit')->where('id',$formActive)->first();

       // $masterplant = Prj_Subproject::where('pid',$masterplant_org->name_of_unit)->where('status','1')->get();

         $masterplant = Prj_Subproject::select('prj_subproject.*', 'factory_address_details.Approve_status')
        ->leftJoin('factory_plant_machineries', 'prj_subproject.id', '=', 'factory_plant_machineries.Plant_Name')
        ->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
        ->where('prj_subproject.pid', $masterplant_org->name_of_unit)
        ->where('prj_subproject.status', '1')
        ->get();
        //dd($masterplant);


        $uom = Factory_Uom::all();
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
        $materials = Master_Material_data::select('prj_material.*','materialmanagement_add_material.Approve_status')
        ->leftJoin('materialmanagement_add_material','prj_material.id','=','materialmanagement_add_material.Material_Name')
        ->where('materialmanagement_add_material.Approve_status',null)
        ->orWhere('materialmanagement_add_material.Approve_status','=','REJECT')
        ->orWhere('materialmanagement_add_material.Approve_status','!=','APPROVE')
        ->orWhere('prj_material.status','1')
        ->get();

        $plantmach = Factory_Plant_Machinery::where('factory_id', $editID)->get();
        $plantCount = $plantmach->count();
        $plantmach_arr = array();
        $machineCount = 0;
        $warrantyCount = 0;
        foreach ($plantmach as $val) {
            $val->machinename = Factory_Plant_Machineries_Machine_Name::where('factory_plant_machineries_id', $val->id)->get();
            $machineCount += $val->machinename->count();
            $val->warrnty = Factory_Plant_Machineries_Warranty::where('factory_plant_machineries_id', $val->id)->get();
            $warrantyCount += $val->warrnty->count();
            $val->other = Factory_Plant_Machineries_Other::where('factory_plant_machineries_id', $val->id)->get();

            array_push($plantmach_arr, $val);
        }



        return view('FectoryCreater/Step4_addfactory', ['formdata' => $this->activeForm($formActive), 'uom' => $uom, 'product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct, 'plantmach' => $plantmach_arr, 'masterplant' => $masterplant, 'ProductionCapacity' => $ProductionCapacity, 'Duration' => $Duration, 'MachineName' => $Machine_Name, 'Machine_Code' => $Machine_Code, 'Accessories' => $Accessories, 'Specification' => $Specification, 'Make_Model' => $Make_Model, 'Warranty' => $Warranty, 'plantCount' => $plantCount, 'machineCount' => $machineCount, 'warrantyCount' => $warrantyCount,'materials'=>$materials]);
    }

    public function step5($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');

        $Amenitiess = Factory_Amenitie::where('factory_id', $editID)->first();
        $amentOther = array();
        if (isset($Amenitiess)) {
            $amentOther = Factory_Amenities_Other::where('factory_amenities_id', $Amenitiess->id)->get();
        }

        return view('FectoryCreater.Step5_addfactory', ['formdata' => $this->activeForm($formActive), 'Amenitiess' => $Amenitiess, 'amentOther' => $amentOther]);
    }

    public function step6($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');
        $addresssdetails = Factory_Address_Detail::where('id', $editID)->where('UserID', auth()->user()->id)->first();
        $Electri = Factory_Electricity::where('factory_id', $editID)->get();
        $Electrigenrate = Factory_Electricities_Generator::where('factory_id', $editID)->get();

        return view('FectoryCreater.Step6_addfactory', ['formdata' => $this->activeForm($formActive), 'addresssdetails' => $addresssdetails, 'Electri' => $Electri, 'Electrigenrate' => $Electrigenrate]);
    }

    public function step7($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');

        $warehousetotal = Factory_Warehouse_Room::where('factory_id', $editID)->first();
        $warehouseroom = array();
        $warehouse = array();
        if (isset($warehousetotal)) {
            $warehouseroom = Factory_Warehouse_Rooms_Room_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->get();
            $warehouse = Factory_Warehouse_Rooms_Warehouse_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->get();
        }
        return view('FectoryCreater.Step7_addfactory', ['formdata' => $this->activeForm($formActive), 'warehousetotal' => $warehousetotal, 'warehouseroom' => $warehouseroom, 'warehouse' => $warehouse]);
    }

    public function step8($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');
        $assetdeatils = Asset_Details::all();
        $Organization = prj_organisation::all();

        $officeasst = Factory_Office_Asset::where('factory_id', $editID)->first();
        $assettypee = array();
        if (isset($officeasst)) {
               $assettypee = Factory_Office_Assets_Type::select('factory_office_assets_types.*','aset_stock_entry.description','aset_stock_entry.id as asset_id')
                            ->leftJoin('aset_stock_entry','factory_office_assets_types.Asset_Name','=','aset_stock_entry.id')
                            ->where('factory_office_assets_id', $officeasst->id)->get();
        }

        return view('FectoryCreater.Step8_addfactory', ['formdata' => $this->activeForm($formActive), 'officeasst' => $officeasst,'assetdeatils' => $assetdeatils, 'assettypee' => $assettypee, 'Organization' => $Organization]);
    }

    public function step9($id = null)
    {
        $formActive = session::get('factory_id');

        return view('FectoryCreater.Step9_addfactory', ['formdata' => $this->activeForm($formActive)]);
    }

    public function step10($id = null)
    {
        $formActive = session::get('factory_id');
        $editID = session::get('editID');

        $storee = Factory_Store::where('factory_id', $editID)->first();
        $storesubrack_arr = array();
        $storebinCount = 0;
        $storesubbinCount = 0;
        $storesubrackCount = 0;
        if (isset($storee)) {
            $storesubrack = Factory_Stores_Sub_Rack_No::where('factory_stores_id', $storee->id)->get();
            $storesubrackCount += $storesubrack->count();
            foreach ($storesubrack as $val) {
                $val->storebin = Factory_Stores_Sub_Rack_No_Bin_No::where('factory_stores_sub_rack_no_id', $val->id)->get();
                $storebinCount += $val->storebin->count();
                $arrayyy = array();
                foreach ($val->storebin as $vall) {
                    $vall->storesubbin = Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No::where('factory_stores_sub_rack_no_bin_no_id', $vall->id)->get();
                    $storesubbinCount += $vall->storesubbin->count();
                    array_push($arrayyy, $vall);
                }

                $val->sturesubbinss = $arrayyy;
                array_push($storesubrack_arr, $val);
            }
        }
        $shelf = Factory_Shelf_Details::where('factory_id', $editID)->first();
        $shelfno_arr = array();
        $shelfnoCount = 0;
        $subshelfsssCount = 0;
        if (isset($shelf)) {
            $shelfno = Factory_Shelf_Details_Shelf_No::where('factory_shelf_details_id', $shelf->id)->get();
            $shelfnoCount += $shelfno->count();
            foreach ($shelfno as $val) {
                $val->subshelfsss = Factory_Shelf_Details_Shelf_No_Sub_Shelf_No::where('factory_shelf_details_shelf_no_id', $val->id)->get();
                $subshelfsssCount +=  $val->subshelfsss->count();

                array_push($shelfno_arr, $val);
            }
        }

        return view('FectoryCreater.Step10_addfactory', ['formdata' => $this->activeForm($formActive), 'storee' => $storee, 'storesubrack' => $storesubrack_arr, 'shelf' => $shelf, 'shelfno' => $shelfno_arr, 'storebinCount' => $storebinCount, 'storesubbinCount' => $storesubbinCount, 'storesubrackCount' => $storesubrackCount, 'shelfnoCount' => $shelfnoCount, 'subshelfsssCount' => $subshelfsssCount]);
    }


    public function destroy($id)
    {
        Factory_Address_Detail::where('id', $id)->where('UserID', auth()->user()->id)->delete();
        Factory_Address::where('factory_id', $id)->delete();

        $statury = Factory_Statutory_Detail::where('factory_id', $id)->first();
        if (isset($statury)) {
            Factory_Statutory_Detail_Other::where('factory_statutory_details_id', $statury->id)->delete();
        }
        Factory_Statutory_Detail::where('factory_id', $id)->delete();

        $landbulding = Factory_Land_Building::where('factory_id', $id)->first();
        if (isset($landbulding)) {
            Factory_Land_Building_Boundary_Type::where('factory_land_building_id', $landbulding->id)->delete();
            factory_land_building_attachement::where('factory_land_building_id', $landbulding->id)->delete();
            Factory_Land_Building_Other::where('factory_land_building_id', $landbulding->id)->delete();
        }
        Factory_Land_Building::where('factory_id', $id)->delete();

        $plantmach = Factory_Plant_Machinery::where('factory_id', $id)->get();
        if (isset($plantmach)) {
            foreach ($plantmach as $val) {
                Factory_Plant_Machineries_Machine_Name::where('factory_plant_machineries_id', $val->id)->delete();
                Factory_Plant_Machineries_Warranty::where('factory_plant_machineries_id', $val->id)->delete();
                Factory_Plant_Machineries_Other::where('factory_plant_machineries_id', $val->id)->delete();
            }
        }
        Factory_Plant_Machinery::where('factory_id', $id)->delete();

        $Amenitiess = Factory_Amenitie::where('factory_id', $id)->first();
        if (isset($Amenitiess)) {
            Factory_Amenities_Other::where('factory_amenities_id', $Amenitiess->id)->delete();
        }
        Factory_Amenitie::where('factory_id', $id)->delete();

        Factory_Electricity::where('factory_id', $id)->delete();
        Factory_Electricities_Generator::where('factory_id', $id)->delete();

        $warehousetotal = Factory_Warehouse_Room::where('factory_id', $id)->first();
        if (isset($warehousetotal)) {
            Factory_Warehouse_Rooms_Room_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->delete();
            Factory_Warehouse_Rooms_Warehouse_Name::where('factory_warehouse_rooms_id', $warehousetotal->id)->delete();
        }
        Factory_Warehouse_Room::where('factory_id', $id)->delete();

        $officeasst = Factory_Office_Asset::where('factory_id', $id)->first();
        if (isset($officeasst)) {
            Factory_Office_Assets_Type::where('factory_office_assets_id', $officeasst->id)->delete();
        }
        Factory_Office_Asset::where('factory_id', $id)->delete();

        $storee = Factory_Store::where('factory_id', $id)->first();
        if (isset($storee)) {
            $storesubrack = Factory_Stores_Sub_Rack_No::where('factory_stores_id', $storee->id)->get();
            if (isset($storesubrack)) {
                foreach ($storesubrack as $val) {
                    $val->storebin = Factory_Stores_Sub_Rack_No_Bin_No::where('factory_stores_sub_rack_no_id', $val->id)->get();
                    foreach ($val->storebin as $vall) {
                        $vall->storesubbin = Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No::where('factory_stores_sub_rack_no_bin_no_id', $vall->id)->delete();
                    }
                    Factory_Stores_Sub_Rack_No_Bin_No::where('factory_stores_sub_rack_no_id', $val->id)->delete();
                }
            }
            Factory_Stores_Sub_Rack_No::where('factory_stores_id', $storee->id)->delete();
        }
        Factory_Store::where('factory_id', $id)->delete();

        $shelf = Factory_Shelf_Details::where('factory_id', $id)->first();
        if (isset($shelf)) {
            $shelfno = Factory_Shelf_Details_Shelf_No::where('factory_shelf_details_id', $shelf->id)->get();
            if (isset($shelfno)) {
                foreach ($shelfno as $val) {
                    Factory_Shelf_Details_Shelf_No_Sub_Shelf_No::where('factory_shelf_details_shelf_no_id', $val->id)->delete();
                }
            }
            Factory_Shelf_Details_Shelf_No::where('factory_shelf_details_id', $shelf->id)->delete();
        }
        Factory_Shelf_Details::where('factory_id', $id)->delete();

        return redirect('FactoryCreater/List')->with('success', 'Deleted Successfully....');
    }


    public function getStates($id)
    {
        $states = State::where('country_id', $id)->get();
        return response()->json($states);
    }

    public function getCities($id)
    {
        $cities = City::where('sid', $id)->where('status','1')->get();
        return response()->json($cities);
    }

    public function getsubproduct($id)
    {
        $subproduct = Factory_Sub_Product::where('product_id', $id)->get();
        return response()->json($subproduct);
    }
    public function getassetdeatsilsajax($id)
    {
        $assetdetails=Asset_Details::select('aset_stock_entry.*','prj_organisation.organisation as orgname','vw_aset_stock_assaignment.fullname')
            ->leftJoin('prj_organisation','aset_stock_entry.organisation','=','prj_organisation.id')
            ->leftJoin('vw_aset_stock_assaignment','aset_stock_entry.auto_serial','=','vw_aset_stock_assaignment.asset_no')
            ->where('aset_stock_entry.id',$id)
            ->get();
        return response()->json($assetdetails);
    }

    public function getsubsubproduct($id)
    {
        $subsubproduct = Factory_Sub_Sub_Product::where('sub_product_id', $id)->get();
        return response()->json($subsubproduct);
    }

    public function getmachinecode($id)
    {
        $machinecode = Master_Machine_Code::where('Machine_Name_id', $id)->get();
        return response()->json($machinecode);
    }
    public function getplantnamedetails($id)
    {
        //$machinecode = Master_Machine_Code::where('Machine_Name_id', $id)->get();
        $plantdetails=Factory_Plant_Machinery::select('prj_subproject.*','prj_organisation.organisation','prj_organisation.id as orgid')
                 ->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
                 ->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
                 ->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
                 ->where('factory_address_details.name_of_unit',$id)
                 ->where('factory_address_details.Approve_status', 'APPROVE')
                 ->get();
        return response()->json($plantdetails);
    }
    public function getbudetails(Request $request,$id)
    {
        $prjid = $request->prjid;
        $budetails=Module_Prj_Assignment::select('module_prj_assignment.*','module_bsns_unit.*')
                 ->leftJoin('module_bsns_unit', 'module_prj_assignment.bsnsunit_id', '=', 'module_bsns_unit.id')
                 ->where('module_prj_assignment.prj_id',$prjid)
                 ->where('module_prj_assignment.subprj_id', $id)
                 ->get();
        return response()->json($budetails);
    }

    public function getorgnames($id)
    {
        $plantdetails=Factory_Plant_Machinery::select('prj_organisation.organisation','prj_organisation.id as orgid')
                 ->leftJoin('prj_subproject', 'factory_plant_machineries.Plant_Name', '=', 'prj_subproject.id')
                 ->leftJoin('factory_address_details', 'factory_plant_machineries.factory_id', '=', 'factory_address_details.id')
                 ->leftJoin('prj_organisation', 'factory_address_details.organization', '=', 'prj_organisation.id')
                 ->where('factory_plant_machineries.Plant_Name',$id)
                 ->get();
        return response()->json($plantdetails);
    }
    

    public function getaccessories($id)
    {
        $accessories = Master_Accessories::where('Machine_Code_id', $id)->get();
        return response()->json($accessories);
    }

    public function deletefile($id, $name)
    {
        if ($name != 'add_field_attachement_manually') {
            $file = Factory_Statutory_Detail::where('id', $id)->update([$name => '']);
        } else {
            $file = Factory_Statutory_Detail_Other::where('id', $id)->update([$name => '']);
        }

        return response()->json($file);
    }
    public function deletefile_boundary($id, $name)
    {
        $file = null;
        if ($name == 'boundary_attachement') {
            $file = Factory_Land_Building_Boundary_Type::find($id);
            if ($file) {
                $file->delete();
                return response()->json(['message' => 'File deleted successfully.']);
            } else {
                return response()->json(['error' => 'File not found.'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid file name.'], 400);
        }
    }


    public function FilterData(Request $request)
    {
        $filters = $request->input('filters', []);

        if (count($filters) > 0) {

            $data = Factory_Approve::whereIn('action', $filters)->where('status', 1)->get();

            $data_arr = array();
            foreach ($data as $val) {
                $val->factory = Factory_Address_Detail::where('id', $val->factory_id)->orderBy('created_at', 'DESC')->first();
                if (!empty($val->factory)) {
                    $val->statess = State::find($val->factory->state);
                    $val->districtss = City::find($val->factory->district);
                    $val->gst = Master_Statutory_GST::find($val->factory->GST);
                    $val->factoryLicense = Master_Statutory_Factory_License_No::find($val->factory->Factory_License_No);
                    $val->org = Factory_Organisation::where('id', $val->factory->organization)->first();
                    $val->unitname = Factory_Name_Of_Unit::where('id', $val->factory->name_of_unit)->first();
                    $val->plantsCount = Factory_Plant_Machinery::where('factory_id', $val->factory->id)->count();
                    $val->statutory = Factory_Statutory_Detail::where('factory_id', $val->factory->id)->first();
                    if (!empty($val->statutory)) {
                        $val->gst = Master_Statutory_GST::find($val->statutory->gst_no);
                    }
                    if (!empty($val->statutory)) {
                        $val->factoryLicense = Master_Statutory_Factory_License_No::find($val->statutory->factory_license_no);
                    }
                    $val->landbuilding = Factory_Land_Building::where('factory_id', $val->factory->id)->first();
                    if (!empty($val->landbuilding)) {
                        $val->landtype = Master_Land_Building::find($val->landbuilding->land_type);
                    }
                    if (!empty($val->landbuilding)) {
                        $val->landarea = Master_Land_Area::find($val->landbuilding->land_area);
                    }
                    $val->store = Factory_Store::where('factory_id', $val->factory->id)->first();
                    $val->WareHouseRoom = Factory_Warehouse_Room::where('factory_id', $val->factory->id)->first();
                }
                array_push($data_arr, $val);
            }

            if ($data->count() > 0) {
                $response = array(
                    'success' => true,
                    'action' => $data_arr
                );
                return response()->json($response);
            }
        }

        return response()->json(array('success' => false, 'message' => 'No data found.'));
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

    public function DownloadFilteredData(Request $request)
    {
        $allData = Factory_Address_Detail::orderBy('id', 'DESC')->get();

        $AllData_Arrr = array();
        foreach ($allData as $val) {
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

            array_push($AllData_Arrr, $val);
        }

        $Checkbox = CheckBox::where('userID', auth()->user()->id)->where('tableID', 1)->get();

        $Checkbox_Arr = [];
        foreach ($Checkbox as $val) {
            $valuee = $val->CheckBox;
            array_push($Checkbox_Arr, $valuee);
        }

        $d = [];
        foreach ($AllData_Arrr as $key => $val) {
            $rowData = [
                "SL. No." => $key + 1,
                "Creater Name" => isset($val->user->fullname) && $val->user->fullname != '' ? $val->user->fullname : '',
                "Date & Time" => isset($val->created_at) && $val->created_at != '' ? date('d-m-Y H:i:s A', strtotime($val->created_at)) : '',
                "Organization" => isset($val->org->organisation) && $val->org->organisation != '' ? $val->org->organisation : '',
                "Name Of The Unit" => isset($val->unitname->pname) && $val->unitname->pname != '' ? $val->unitname->pname : '',
                "District/State" => (isset($val->districtss->distname) && $val->districtss->distname != '' ? $val->districtss->distname : '') . '/' . (isset($val->statess->sname) && $val->statess->sname != '' ? $val->statess->sname : ''),
                "Total Plant" => $val->plantsCount,
                "GST No." => isset($val->gst->gst_no) && $val->gst->gst_no != '' ? $val->gst->gst_no : '',
                "Factory License" => isset($val->factoryLicense->factory_license_no) && $val->factoryLicense->factory_license_no != '' ? $val->factoryLicense->factory_license_no : '',
                "Land Type" => isset($val->landtype->land_type) && $val->landtype->land_type != '' ? $val->landtype->land_type : '',
                "Total Godown" => isset($val->WareHouseRoom->Total_Warehouse) && $val->WareHouseRoom->Total_Warehouse != '' ? $val->WareHouseRoom->Total_Warehouse : '',
                "Total Rack" => isset($val->store->Total_Rack) && $val->store->Total_Rack != '' ? $val->store->Total_Rack : '',
                "Rack Capacity" => isset($val->store->Rack_Capacity) && $val->store->Rack_Capacity != '' ? $val->store->Rack_Capacity : '',
                "Total Bin" => isset($val->store->Total_Bin) && $val->store->Total_Bin != '' ? $val->store->Total_Bin : '',
                "Bin Cap." => isset($val->store->Total_Bin_Capacity) && $val->store->Total_Bin_Capacity != '' ? $val->store->Total_Bin_Capacity : '',
                "Status" => ($val->Approve_status == 'APPROVE') ? 'APPROVED' : (($val->Approve_status == 'REJECT') ? 'REJECTED' : (($val->Approve_status == 'RECHECK') ? 'RECHECK' : (($val->Approve_status == 'OBJECT') ? 'OBJECT' : (($val->Approve_status == 'HOLD') ? 'HOLD' :
                    'Pending')))),
                "Pending With" => ($val->Approve_status === 'FORWARD' || ($val->Approve_status == '' && isset($val->store->status) && $val->store->status != 1)) ?
                    'Pending With ' . (function () use ($val) {
                        $names = [];
                        if ($val->PendingWith != null) {
                            foreach ($val->PendingWith as $name) {
                                if (isset($name->name) && $name->name != '') {
                                    $names[] = $name->name;
                                }
                            }
                        }
                        return implode(', ', $names);
                    })() : (($val->Approve_status === 'RECHECK' || $val->Approve_status === 'OBJECT') ?
                        (isset($val->user->name) && $val->user->name != '' ? 'Pending With ' . $val->user->name : '') : ''),

            ];

            if (count($Checkbox_Arr) > 0) {
                $filteredRow = [];
                foreach ($rowData as $key2 => $value) {
                    if (in_array($key2, $Checkbox_Arr)) {
                        $filteredRow[$key2] = $value;
                    }
                }
                $d[] = $filteredRow;
            } else {
                $d[] = $rowData;
            }
        }

        $file = "factory_data.csv";
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
}
