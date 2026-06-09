<?php

namespace App\Http\Controllers\FactoryCreater;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{City, Country, State};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Name_Of_Unit, Factory_Address_Detail, Factory_Address, Factory_Statutory_Detail, Factory_Statutory_Detail_Other, Factory_Land_Building, Factory_Land_Building_Other, Factory_Land_Building_Boundary_Type, factory_land_building_attachement, Factory_Plant_Machinery, Factory_Plant_Machineries_Machine_Name, Factory_Plant_Machineries_Warranty, Factory_Plant_Machineries_Other, Factory_Uom, Factory_Amenitie, Factory_Amenities_Other, Factory_Electricity, Factory_Electricities_Generator, Factory_Warehouse_Room, Factory_Warehouse_Rooms_Room_Name, Factory_Warehouse_Rooms_Warehouse_Name, Factory_Office_Asset, Factory_Office_Assets_Type, Factory_Store, Factory_Stores_Sub_Rack_No, Factory_Stores_Sub_Rack_No_Bin_No, Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No, Factory_Shelf_Details, Factory_Shelf_Details_Shelf_No, Factory_Shelf_Details_Shelf_No_Sub_Shelf_No, Factory_Product, Factory_Plant_Name, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Approve};
use Session;


class FactoryCreaterController extends Controller
{

    public function address(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'organization' => 'required',
                'name_of_unit' => 'required',
                'country' => 'required',
                'state' => 'required',
                'district' => 'required',
                'pincode' => 'required',
                'address.*' => 'required',
            ]);
        }

        if ($request->edit != '') {
            $address = Factory_Address_Detail::where('id', $request->edit)->first();
        } else {
            $address = new Factory_Address_Detail;
            $address->userID = auth()->user()->id;
            $address->Forward_Status = 0;
        }

        $address->organization = $request->organization;
        $address->name_of_unit = $request->name_of_unit;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->district = $request->district;
        $address->pincode = $request->pincode;
        $address->remarks = $request->remarks;
        $address->status = $request->status ? 1 : 0;

        $address->save();

        $res = $request->input();

        foreach ($res['address'] as $key => $val) {
            $addressID = isset($res['addressID'][$key]) ? $res['addressID'][$key] : '';
            if ($addressID != '') {
                if (isset($res['address'][$key]) && $res['address'][$key] != '') {
                    $adrr = Factory_Address::where('id', $addressID)->where('factory_id', $request->edit)->update(['address' => $res['address'][$key]]);
                }
            } else {
                $adrr = new  Factory_Address;
                $adrr->factory_id = $address->id;
                if (isset($res['address'][$key]) && $res['address'][$key] != '') {
                    $adrr->address = $res['address'][$key];
                }
                $adrr->save();
            }
        }

        Session::put('factory_id', $address->id);
        session::put('editID', $address->id);

        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step2')->with('success', 'Successfull....');
        }
    }

    public function statutory(Request $request)
    {
        if ($request->draft != 1) {
            if ($request->edit != '') {
                $required = [
                    'gst_no' => 'required',
                    'pan' => 'required',
                    'factory_license_no' => 'required',
                    'labour_license_no' => 'required',
                    'pollution_certificate_no' => 'required',
                ];
            } else {
                $required = [
                    'gst_no' => 'required',
                    'gst_in_certificate_attachement' => 'required',
                    'pan' => 'required',
                    'pan_attachement' => 'required',
                    'factory_license_no' => 'required',
                    'factory_license_attachement' => 'required',
                    'labour_license_no' => 'required',
                    'labour_license_attachement' => 'required',
                    'pollution_certificate_no' => 'required',
                    'pollution_certificate_attachement' => 'required',
                ];
            }
            $request->validate($required);
        }

        $factory_id = Session::get('factory_id');

        if ($request->edit != '') {
            $statutory = Factory_Statutory_Detail::where('id', $request->edit)->first();
        } else {
            $statutory = new Factory_Statutory_Detail;

            $statutory->factory_id = $factory_id;
        }
        $statutory->gst_no = $request->gst_no;
        if ($request->file('gst_in_certificate_attachement') != '') {
            $name = $request->file('gst_in_certificate_attachement')->getClientOriginalName();
            $statutory->gst_in_certificate_attachement = $request->file('gst_in_certificate_attachement')->storeAs('FactoryCreater', $name);
        }

        $statutory->pan = $request->pan;
        if ($request->file('pan_attachement') != '') {
            $name = $request->file('pan_attachement')->getClientOriginalName();
            $statutory->pan_attachement = $request->file('pan_attachement')->storeAs('FactoryCreater', $name);
        }
        $statutory->factory_license_no = $request->factory_license_no;
        if ($request->file('factory_license_attachement') != '') {
            $name = $request->file('factory_license_attachement')->getClientOriginalName();
            $statutory->factory_license_attachement = $request->file('factory_license_attachement')->storeAs('FactoryCreater', $name);
        }
        $statutory->labour_license_no = $request->labour_license_no;
        if ($request->file('labour_license_attachement') != '') {
            $name = $request->file('labour_license_attachement')->getClientOriginalName();
            $statutory->labour_license_attachement = $request->file('labour_license_attachement')->storeAs('FactoryCreater', $name);
        }
        $statutory->pollution_certificate_no = $request->pollution_certificate_no;
        if ($request->file('pollution_certificate_attachement') != '') {
            $name = $request->file('pollution_certificate_attachement')->getClientOriginalName();
            $statutory->pollution_certificate_attachement = $request->file('pollution_certificate_attachement')->storeAs('FactoryCreater', $name);
        }
        if ($request->Remarks != '') {
            $statutory->Remarks = $request->Remarks;
        }
        $statutory->status = $request->status ? 1 : 0;

        $statutory->save();

        $res = $request->input();

        if (isset($res['idd']) && count($res['idd']) != '' || isset($request->editother) && $request->editother != '') {
            foreach ($res['idd'] as $key => $val) {
                $editother = isset($res['editother'][$key]) ? $res['editother'][$key] : '';

                if ($editother != '') {
                    if ($request->file('add_field_attachement_manually' . $val) != '') {
                        $name = $request->file('add_field_attachement_manually' . $val)->getClientOriginalName();
                        $updatefile['add_field_attachement_manually'] = $request->file('add_field_attachement_manually' . $val)->storeAs('FactoryCreater', $name);

                        $statutory_details = Factory_Statutory_Detail_Other::where('id', $editother)->update(['add_field_attachement_manually' => $updatefile['add_field_attachement_manually']]);
                    }

                    $statutory_details = Factory_Statutory_Detail_Other::where('id', $editother)->update(['add_field_manually' => $res['add_field_manually'][$key] ?? '', 'add_field_manually_second' => $res['add_field_manually_second'][$key] ?? '']);
                } else {
                    $statutory_details = new Factory_Statutory_Detail_Other;
                    $statutory_details->factory_statutory_details_id = $statutory->id;
                    if ($res['add_field_manually'][$key] != '') {
                        $statutory_details->add_field_manually = $res['add_field_manually'][$key];
                    }
                    if ($res['add_field_manually_second'][$key] != '') {
                        $statutory_details->add_field_manually_second = $res['add_field_manually_second'][$key];
                    }
                    if ($request->file('add_field_attachement_manually' . $val) != '') {
                        $name = $request->file('add_field_attachement_manually' . $val)->getClientOriginalName();
                        $statutory_details->add_field_attachement_manually = $request->file('add_field_attachement_manually' . $val)->storeAs('FactoryCreater', $name);
                    }

                    $statutory_details->save();
                }
            }
        }

        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step3')->with('success', 'Successfull....');
        }
    }

    public function Land_Building(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'land_type' => 'required',
                'land_area' => 'required',
                'open_area' => 'required',
                'cover_area' => 'required',
                'building_area' => 'required',
                'building_type' => 'required',
                'boundary_height' => 'required',
                'boundary_width' => 'required',
                'window' => 'required',
                'gate' => 'required',
                'boundary_type' => 'required',
                'add_field_name_manually.*' => 'required',
                'enter_manually_details.*' => 'required',
            ]);
        }

        $factory_id = Session::get('factory_id');

        if ($request->edit != '') {
            $landbuilding = Factory_Land_Building::where('id', $request->edit)->first();
        } else {
            $landbuilding = new Factory_Land_Building;
            $landbuilding->factory_id = $factory_id;
        }

        $landbuilding->land_type = $request->land_type;
        $landbuilding->land_area = $request->land_area;
        $landbuilding->open_area = $request->open_area;
        $landbuilding->cover_area = $request->cover_area;
        $landbuilding->building_area = $request->building_area;
        $landbuilding->building_type = $request->building_type;
        $landbuilding->boundary_height = $request->boundary_height;
        $landbuilding->boundary_width = $request->boundary_width;
        $landbuilding->window = $request->window;
        $landbuilding->gate = $request->gate;
        $landbuilding->remark = $request->remark;
        $landbuilding->status = $request->status ? 1 : 0;

        $landbuilding->save();

        $res = $request->input();


        foreach ($res['idd'] as $key => $val) {
            $boundaryID = isset($res['boundaryID'][$key]) ? $res['boundaryID'][$key] : '';
            if ($boundaryID != '') {
                if ($request->file('attachement' . $val) != '') {
                    $name = $request->file('attachement' . $val)->getClientOriginalName();
                    $updatefile['attachement'] = $request->file('attachement' . $val)->storeAs('landBuilding', $name);

                    $boundarytype = Factory_Land_Building_Boundary_Type::where('id', $boundaryID)->update(['attachement' => $updatefile['attachement']]);
                }

                $boundarytype = Factory_Land_Building_Boundary_Type::where('id', $boundaryID)->update(['boundary_type' => $res['boundary_type'][$key] ?? '']);
            } else {
                $boundarytype = new Factory_Land_Building_Boundary_Type;
                $boundarytype->factory_land_building_id = $landbuilding->id;
                if (isset($res['boundary_type'][$key]) && $res['boundary_type'][$key] != '') {
                    $boundarytype->boundary_type = $res['boundary_type'][$key];
                }

                if ($request->file('attachement' . $val) != '') {
                    $name = $request->file('attachement' . $val)->getClientOriginalName();
                    $boundarytype->attachement = $request->file('attachement' . $val)->storeAs('landBuilding', $name);
                }

                $boundarytype->save();
            }
        }


        if (isset($res['add_field_name_manually']) && $res['add_field_name_manually'] != '' || isset($res['otherID']) && $res['otherID'] != '') {
            foreach ($res['add_field_name_manually'] as $key => $val) {
                $otherID = isset($res['otherID'][$key]) ? $res['otherID'][$key] : '';
                if ($otherID != '') {
                    $others = Factory_Land_Building_Other::where('id', $otherID)->update(['add_field_name_manually' => $res['add_field_name_manually'][$key] ?? '', 'enter_manually_details' => $res['enter_manually_details'][$key] ?? '']);
                } else {
                    $others = new Factory_Land_Building_Other;
                    $others->factory_land_building_id = $landbuilding->id;
                    if (isset($res['add_field_name_manually'][$key]) && $res['add_field_name_manually'][$key] != '') {
                        $others->add_field_name_manually = $res['add_field_name_manually'][$key];
                    }
                    if (isset($res['enter_manually_details'][$key]) && $res['enter_manually_details'][$key] != '') {
                        $others->enter_manually_details = $res['enter_manually_details'][$key];
                    }

                    $others->save();
                }
            }
        }
        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step4')->with('success', 'Successfull....');
        }
    }

    public function Plant_Machinery(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Plant_Name' => 'required',
                'Production_Capacity' => 'required',
                'UOM' => 'required',
                'Duration' => 'required',
                'Date_Of_Purchase' => 'required',
                'Machine_Company_Name' => 'required',
                'Machine_Name.*' => 'required',
                'Machine_Code.*' => 'required',
                'Accessories.*' => 'required',
                'Specification.*' => 'required',
                'Make_Model.*' => 'required',
                'Warranty.*' => 'required',
                'Production_Capacitys.*' => 'required',
                'UOMs.*' => 'required',
                'Add_Field_Name_Manually.*' => 'required',
                'Enter_Manually_Details.*' => 'required',
            ]);
        }

        $factory_id = Session::get('factory_id');

        $res = $request->input();

        if (isset($res['Plant_Name']) && $res['Plant_Name'] != '') {
            foreach ($res['Plant_Name'] as $key => $val) {
                $edit = isset($res['edit'][$key]) ? $res['edit'][$key] : '';
                if ($edit != '') {
                    $plantmachinary = Factory_Plant_Machinery::where('id', $edit)->update(['Plant_Name' => $res['Plant_Name'][$key] ?? '', 'Production_Capacity' => $res['Production_Capacity'][$key] ?? '', 'Product' => $res['Product'][$key] ?? '', 'Sub_product' => $res['Sub_product'][$key] ?? '', 'Sub_Sub_product' => $res['Sub_Sub_product'][$key] ?? '', 'UOM' => $res['UOM'][$key] ?? '', 'Duration' => $res['Duration'][$key] ?? '', 'Date_Of_Purchase' => $res['Date_Of_Purchase'][$key] ?? '', 'Machine_Company_Name' => $res['Machine_Company_Name'][$key] ?? '', 'Remarks' => $res['Remarks'][$key] ?? '']);
                } else {
                    $plantmachinary = new Factory_Plant_Machinery;

                    $plantmachinary->factory_id = $factory_id;
                    if (isset($res['Plant_Name'][$key]) && $res['Plant_Name'][$key] != '') {
                        $plantmachinary->Plant_Name = $res['Plant_Name'][$key];
                    }
                    if (isset($res['Production_Capacity'][$key]) && $res['Production_Capacity'][$key] != '') {
                        $plantmachinary->Production_Capacity = $res['Production_Capacity'][$key];
                    }
                    if (isset($res['Product'][$key]) && $res['Product'][$key] != '') {
                        $plantmachinary->Product = $res['Product'][$key];
                    }
                    if (isset($res['Sub_product'][$key]) && $res['Sub_product'][$key] != '') {
                        $plantmachinary->Sub_product = $res['Sub_product'][$key];
                    }
                    if (isset($res['Sub_Sub_product'][$key]) && $res['Sub_Sub_product'][$key] != '') {
                        $plantmachinary->Sub_Sub_product = $res['Sub_Sub_product'][$key];
                    }
                    if (isset($res['UOM'][$key]) && $res['UOM'][$key] != '') {
                        $plantmachinary->UOM = $res['UOM'][$key];
                    }
                    if (isset($res['Duration'][$key]) && $res['Duration'][$key] != '') {
                        $plantmachinary->Duration = $res['Duration'][$key];
                    }
                    if (isset($res['Date_Of_Purchase'][$key]) && $res['Date_Of_Purchase'][$key] != '') {
                        $plantmachinary->Date_Of_Purchase = $res['Date_Of_Purchase'][$key];
                    }
                    if (isset($res['Machine_Company_Name'][$key]) && $res['Machine_Company_Name'][$key] != '') {
                        $plantmachinary->Machine_Company_Name = $res['Machine_Company_Name'][$key];
                    }
                    if (isset($res['Remarks'][$key]) && $res['Remarks'][$key] != '') {
                        $plantmachinary->Remarks = $res['Remarks'][$key];
                    }
                    $plantmachinary->status = $request->status ? 1 : 0;

                    $plantmachinary->save();
                }

                if ($edit) {
                    $plantmachinary_ID = $edit;
                } else {
                    $plantmachinary_ID = $plantmachinary->id;
                }

                if (isset($res['Machine_Name'][$key]) && $res['Machine_Name'][$key] != '') {
                    foreach ($res['Machine_Name'][$key] as $key1 => $val1) {
                        $machinenameID = isset($res['machinenameID'][$key][$key1]) ? $res['machinenameID'][$key][$key1] : '';
                        if ($machinenameID != '') {

                            $machinenames = Factory_Plant_Machineries_Machine_Name::where('id', $machinenameID)->update(['Machine_Name' => $res['Machine_Name'][$key][$key1] ?? '', 'Machine_Code' => $res['Machine_Code'][$key][$key1] ?? '', 'Accessories' => $res['Accessories'][$key][$key1] ?? '', 'Specification' => $res['Specification'][$key][$key1] ?? '', 'Make_Model' => $res['Make_Model'][$key][$key1] ?? '']);

                            if ($request->file('Attachement' . $key . $key1) != '') {
                                $name = $request->file('Attachement' . $key . $key1)->getClientOriginalName();
                                $machinen['Attachement'] = $request->file('Attachement' . $key . $key1)->storeAs('PlantMachineries', $name);

                                Factory_Plant_Machineries_Machine_Name::where('id', $machinenameID)->update(['Attachement' => $machinen['Attachement']]);
                            }
                            if ($request->file('Attachements' . $key . $key1) != '') {
                                $name = $request->file('Attachements' . $key . $key1)->getClientOriginalName();
                                $machinenam['Attachements'] = $request->file('Attachements' . $key . $key1)->storeAs('PlantMachineries', $name);

                                Factory_Plant_Machineries_Machine_Name::where('id', $machinenameID)->update(['Attachements' => $machinenam['Attachements']]);
                            }
                        } else {
                            $machinenames = new Factory_Plant_Machineries_Machine_Name;
                            $machinenames->factory_plant_machineries_id = $plantmachinary_ID;
                            if (isset($res['Machine_Name'][$key][$key1]) && $res['Machine_Name'][$key][$key1] != '') {
                                $machinenames->Machine_Name = $res['Machine_Name'][$key][$key1];
                            }
                            if ($request->file('Attachement' . $key . $key1) != '') {
                                $name = $request->file('Attachement' . $key . $key1)->getClientOriginalName();
                                $machinenames->Attachement = $request->file('Attachement' . $key . $key1)->storeAs('PlantMachineries', $name);
                            }
                            if (isset($res['Machine_Code'][$key][$key1]) && $res['Machine_Code'][$key][$key1] != '') {
                                $machinenames->Machine_Code = $res['Machine_Code'][$key][$key1];
                            }
                            if (isset($res['Accessories'][$key][$key1]) && $res['Accessories'][$key][$key1] != '') {
                                $machinenames->Accessories = $res['Accessories'][$key][$key1];
                            }
                            if ($request->file('Attachements' . $key . $key1) != '') {
                                $name = $request->file('Attachements' . $key . $key1)->getClientOriginalName();
                                $machinenames->Attachements = $request->file('Attachements' . $key . $key1)->storeAs('PlantMachineries', $name);
                            }
                            if (isset($res['Specification'][$key][$key1]) && $res['Specification'][$key][$key1] != '') {
                                $machinenames->Specification = $res['Specification'][$key][$key1];
                            }
                            if (isset($res['Make_Model'][$key][$key1]) && $res['Make_Model'][$key][$key1] != '') {
                                $machinenames->Make_Model = $res['Make_Model'][$key][$key1];
                            }

                            $machinenames->save();
                        }
                    }
                }

                if (isset($res['Warranty'][$key]) && $res['Warranty'][$key] != '') {
                    foreach ($res['Warranty'][$key] as $key2 => $val2) {
                        $warrntyID = isset($res['warrntyID'][$key][$key2]) ? $res['warrntyID'][$key][$key2] : '';
                        if ($warrntyID != '') {
                            $warranty = Factory_Plant_Machineries_Warranty::where('id', $warrntyID)->update(['Warranty' => $res['Warranty'][$key][$key2] ?? '', 'Production_Capacitys' => $res['Production_Capacitys'][$key][$key2] ?? '', 'UOMs' => $res['UOMs'][$key][$key2] ?? '']);
                        } else {
                            $warranty = new Factory_Plant_Machineries_Warranty;
                            $warranty->factory_plant_machineries_id = $plantmachinary_ID;
                            if (isset($res['Warranty'][$key][$key2]) && $res['Warranty'][$key][$key2] != '') {
                                $warranty->Warranty = $res['Warranty'][$key][$key2];
                            }
                            if (isset($res['Production_Capacitys'][$key][$key2]) && $res['Production_Capacitys'][$key][$key2] != '') {
                                $warranty->Production_Capacitys = $res['Production_Capacitys'][$key][$key2];
                            }
                            if (isset($res['UOMs'][$key][$key2]) && $res['UOMs'][$key][$key2] != '') {
                                $warranty->UOMs = $res['UOMs'][$key][$key2];
                            }

                            $warranty->save();
                        }
                    }
                }

                if (isset($res['Add_Field_Name_Manually'][$key]) && $res['Add_Field_Name_Manually'][$key] != '' || isset($res['otherID'][$key]) && $res['otherID'][$key] != '') {
                    foreach ($res['Add_Field_Name_Manually'][$key] as $key3 => $val3) {
                        $otherID = isset($res['otherID'][$key][$key3]) ? $res['otherID'][$key][$key3] : '';
                        if ($otherID != '') {
                            $warranty = Factory_Plant_Machineries_Other::where('id', $otherID)->update(['Add_Field_Name_Manually' => $res['Add_Field_Name_Manually'][$key][$key3] ?? '', 'Enter_Manually_Details' => $res['Enter_Manually_Details'][$key][$key3] ?? '']);
                        } else {
                            $others = new Factory_Plant_Machineries_Other;
                            $others->factory_plant_machineries_id = $plantmachinary_ID;
                            if (isset($res['Add_Field_Name_Manually'][$key][$key2]) && $res['Add_Field_Name_Manually'][$key][$key3] != '') {
                                $others->Add_Field_Name_Manually = $res['Add_Field_Name_Manually'][$key][$key3];
                            }
                            if (isset($res['Enter_Manually_Details'][$key][$key2]) && $res['Enter_Manually_Details'][$key][$key3] != '') {
                                $others->Enter_Manually_Details = $res['Enter_Manually_Details'][$key][$key3];
                            }

                            $others->save();
                        }
                    }
                }
            }
        }
        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step5')->with('success', 'Successfull....');
        }
    }

    public function Amenities(Request $request)
    {
        $factory_id = Session::get('factory_id');

        if (isset($request->edit) && $request->edit != '') {
            $Amenitie = Factory_Amenitie::where('id', $request->edit)->first();
        } else {
            $Amenitie = new Factory_Amenitie;
            $Amenitie->factory_id = $factory_id;
        }

        $Amenitie->Toilet_Count = $request->Toilet_Count;
        $Amenitie->For_Men = $request->For_Men;
        $Amenitie->For_Women = $request->For_Women;
        $Amenitie->WashBasin_Count = $request->WashBasin_Count;
        $Amenitie->Urinals = $request->Urinals;
        $Amenitie->status = $request->status ? 1 : 0;

        $Amenitie->save();

        $res = $request->input();
        if (isset($res['Add_Field_Name_Manually']) && $res['Add_Field_Name_Manually'] != '' || isset($res['other']) && $res['other'] != '') {
            foreach ($res['Add_Field_Name_Manually'] as $key => $val) {
                $other = isset($res['other'][$key]) ? $res['other'][$key] : '';
                if ($other != '') {
                    $others = Factory_Amenities_Other::where('id', $other)->update(['Add_Field_Name_Manually' => $res['Add_Field_Name_Manually'][$key] ?? '', 'Add_Count_Manually' => $res['Add_Count_Manually'][$key] ?? '']);
                } else {
                    $others = new Factory_Amenities_Other;
                    $others->factory_amenities_id = $Amenitie->id;
                    $others->Add_Field_Name_Manually = $res['Add_Field_Name_Manually'][$key] ?? '';
                    $others->Add_Count_Manually = $res['Add_Count_Manually'][$key] ?? '';

                    $others->save();
                }
            }
        }
        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step6')->with('success', 'Successfull....');
        }
    }

    public function Electricity(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Total_Capacity.*' => 'required',
                'Running_Capacity.*' => 'required',
                'Meter.*' => 'required',
                'Sub_Meter.*' => 'required',
                'Source_Of_Electricity.*' => 'required',
                'generator.*' => 'required',
                'Generator_Capacity.*' => 'required',
            ]);
        }
        $factory_id = Session::get('factory_id');

        $res = $request->input();

        Factory_Address_Detail::where('id', $factory_id)->update(['Electricity_remarks' => $res['Electricity_remarks'] ?? '']);
        Factory_Address_Detail::where('id', $request->edit)->update(['Electricity_remarks' => $res['Electricity_remarks'] ?? '']);

        foreach ($res['Total_Capacity'] as $key => $val) {
            $electID = isset($res['electID'][$key]) ? $res['electID'][$key] : '';
            if ($electID != '') {
                $Electricity = Factory_Electricity::where('id', $electID)->update(['Total_Capacity' => $res['Total_Capacity'][$key] ?? '', 'Running_Capacity' => $res['Running_Capacity'][$key] ?? '', 'Meter' => $res['Meter'][$key] ?? '', 'Sub_Meter' => $res['Sub_Meter'][$key] ?? '', 'Source_Of_Electricity' => $res['Source_Of_Electricity'][$key] ?? '']);
            } else {
                $Electricity = new Factory_Electricity;
                $Electricity->factory_id = $factory_id;
                $Electricity->Total_Capacity = $res['Total_Capacity'][$key] ?? '';
                $Electricity->Running_Capacity = $res['Running_Capacity'][$key] ?? '';
                $Electricity->Meter = $res['Meter'][$key] ?? '';
                $Electricity->Sub_Meter = $res['Sub_Meter'][$key] ?? '';
                $Electricity->Source_Of_Electricity = $res['Source_Of_Electricity'][$key] ?? '';
                $Electricity->status = $request->status ? 1 : 0;

                $Electricity->save();
            }
        }

        foreach ($res['generator'] as $key => $val) {
            $generatorID = isset($res['generatorID'][$key]) ? $res['generatorID'][$key] : '';
            if ($generatorID != '') {
                $Generator = Factory_Electricities_Generator::where('id', $generatorID)->update(['generator' => $res['generator'][$key] ?? '', 'Generator_Capacity' => $res['Generator_Capacity'][$key] ?? '']);
            } else {
                $Generator = new Factory_Electricities_Generator;
                $Generator->factory_id = $factory_id;
                $Generator->generator = $res['generator'][$key] ?? '';
                $Generator->Generator_Capacity = $res['Generator_Capacity'][$key] ?? '';
                $Generator->status = $request->status ? 1 : 0;

                $Generator->save();
            }
        }

        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step7')->with('success', 'Successfull....');
        }
    }

    public function WareHouse_Room(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Total_Warehouse' => 'required',
                'Total_Room' => 'required',
                'Room_Name.*' => 'required',
                'Room_Count.*' => 'required',
                'Warehouse_Name.*' => 'required',
                'Count.*' => 'required',
                'Warehouse_Type.*' => 'required',
            ]);
        }

        $factory_id = Session::get('factory_id');

        if (isset($request->edit) && $request->edit != '') {
            $WareHouse = Factory_Warehouse_Room::where('id', $request->edit)->first();
        } else {
            $WareHouse = new Factory_Warehouse_Room;
            $WareHouse->factory_id = $factory_id;
        }
        $WareHouse->Total_Warehouse = $request->Total_Warehouse;
        $WareHouse->Total_Room = $request->Total_Room;
        $WareHouse->Remark = $request->Remark;
        $WareHouse->status = $request->status ? 1 : 0;

        $WareHouse->save();

        $res = $request->input();

        foreach ($res['Room_Name'] as $key => $val) {
            $roomID = isset($res['roomID'][$key]) ? $res['roomID'][$key] : '';
            if ($roomID != '') {
                $rooms = Factory_Warehouse_Rooms_Room_Name::where('id', $roomID)->update(['Room_Name' => $res['Room_Name'][$key] ?? '', 'Room_Count' => $res['Room_Count'][$key] ?? '']);
            } else {
                $rooms = new Factory_Warehouse_Rooms_Room_Name;
                $rooms->factory_warehouse_rooms_id = $WareHouse->id;
                $rooms->Room_Name = $res['Room_Name'][$key] ?? '';
                $rooms->Room_Count = $res['Room_Count'][$key] ?? '';

                $rooms->save();
            }
        }

        foreach ($res['Warehouse_Name'] as $key => $val) {
            $warehouseID = isset($res['warehouseID'][$key]) ? $res['warehouseID'][$key] : '';
            if ($warehouseID != '') {
                $rooms = Factory_Warehouse_Rooms_Warehouse_Name::where('id', $warehouseID)->update(['Warehouse_Name' => $res['Warehouse_Name'][$key] ?? '', 'Count' => $res['Count'][$key] ?? '', 'Warehouse_Type' => $res['Warehouse_Type'][$key] ?? '']);
            } else {
                $Warehousename = new Factory_Warehouse_Rooms_Warehouse_Name;
                $Warehousename->factory_warehouse_rooms_id = $WareHouse->id;
                $Warehousename->Warehouse_Name = $res['Warehouse_Name'][$key] ?? '';
                $Warehousename->Count = $res['Count'][$key] ?? '';
                $Warehousename->Warehouse_Type = $res['Warehouse_Type'][$key] ?? '';

                $Warehousename->save();
            }
        }

        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step8')->with('success', 'Successfull....');
        }
    }


    public function Office_Asset(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Asset_Type.*' => 'required',
                'Asset_Name.*' => 'required',
                'Asset_SL_No.*' => 'required',
                'Date_Of_Purchase.*' => 'required',
                'Supplier_Name.*' => 'required',
                'invoice_No.*' => 'required',
                'QTY.*' => 'required',
                'Organization.*' => 'required',
                'Use_By.*' => 'required',
                'Use_In.*' => 'required',
                'Location.*' => 'required',
                'Furniture_Type.*' => 'required',
                'Furniture_Name.*' => 'required',
                'Furniture_SL_No.*' => 'required',
                'Date_Of_Purchase_Second.*' => 'required',
                'Supplier_Name_Second.*' => 'required',
                'Invoice_No_Second.*' => 'required',
                'QTY_Second.*' => 'required',
                'Organization_Second.*' => 'required',
                'Location_Second.*' => 'required',
                'Use_By_Second.*' => 'required',
                'Used_For.*' => 'required',
                'Other_Item_Details.*' => 'required',
            ]);
        }

        $factory_id = Session::get('factory_id');

        if (isset($request->edit) && $request->edit != '') {
            $officeassets = Factory_Office_Asset::where('id', $request->edit)->first();
        } else {
            $officeassets = new Factory_Office_Asset;
            $officeassets->factory_id = $factory_id;
        }
        $officeassets->Asset_Category = $request->Asset_Category;
        $officeassets->Remark = $request->Remark;
        $officeassets->status = $request->status ? 1 : 0;

        $officeassets->save();

        $res = $request->input();

        foreach ($res['Asset_Type'] as $key => $val) {
            $typeID = isset($res['typeID'][$key]) ? $res['typeID'][$key] : '';
            if ($typeID != '') {
                $assettype = Factory_Office_Assets_Type::where('id', $typeID)->update(['Asset_Type' => $res['Asset_Type'][$key] ?? '', 'Asset_Name' => $res['Asset_Name'][$key] ?? '', 'Asset_SL_No' => $res['Asset_SL_No'][$key] ?? '', 'Date_Of_Purchase' => $res['Date_Of_Purchase'][$key] ?? '', 'Supplier_Name' => $res['Supplier_Name'][$key] ?? '', 'invoice_No' => $res['invoice_No'][$key] ?? '', 'QTY' => $res['QTY'][$key] ?? '', 'Organization' => $res['Organization'][$key] ?? '', 'Use_By' => $res['Use_By'][$key] ?? '', 'Use_In' => $res['Use_In'][$key] ?? '', 'Location' => $res['Location'][$key] ?? '', 'Furniture_Type' => $res['Furniture_Type'][$key] ?? '', 'Furniture_Name' => $res['Furniture_Name'][$key] ?? '', 'Furniture_SL_No' => $res['Furniture_SL_No'][$key] ?? '', 'Date_Of_Purchase_Second' => $res['Date_Of_Purchase_Second'][$key] ?? '', 'Supplier_Name_Second' => $res['Supplier_Name_Second'][$key] ?? '', 'Invoice_No_Second' => $res['Invoice_No_Second'][$key] ?? '', 'QTY_Second' => $res['QTY_Second'][$key] ?? '', 'Organization_Second' => $res['Organization_Second'][$key] ?? '', 'Location_Second' => $res['Location_Second'][$key] ?? '', 'Use_By_Second' => $res['Use_By_Second'][$key] ?? '', 'Used_For' => $res['Used_For'][$key] ?? '', 'Other_Item_Details' => $res['Other_Item_Details'][$key] ?? '']);
            } else {
                $assettype = new Factory_Office_Assets_Type;
                $assettype->factory_office_assets_id = $officeassets->id;
                $assettype->Asset_Type = $res['Asset_Type'][$key] ?? '';
                $assettype->Asset_Name = $res['Asset_Name'][$key] ?? '';
                $assettype->Asset_SL_No = $res['Asset_SL_No'][$key] ?? '';
                $assettype->Date_Of_Purchase = $res['Date_Of_Purchase'][$key] ?? '';
                $assettype->Supplier_Name = $res['Supplier_Name'][$key] ?? '';
                $assettype->invoice_No = $res['invoice_No'][$key] ?? '';
                $assettype->QTY = $res['QTY'][$key] ?? '';
                $assettype->Organization = $res['Organization'][$key] ?? '';
                $assettype->Use_By = $res['Use_By'][$key] ?? '';
                $assettype->Use_In = $res['Use_In'][$key] ?? '';
                $assettype->Location = $res['Location'][$key] ?? '';
                $assettype->Furniture_Type = $res['Furniture_Type'][$key] ?? '';
                $assettype->Furniture_Name = $res['Furniture_Name'][$key] ?? '';
                $assettype->Furniture_SL_No = $res['Furniture_SL_No'][$key] ?? '';
                $assettype->Date_Of_Purchase_Second = $res['Date_Of_Purchase_Second'][$key] ?? '';
                $assettype->Supplier_Name_Second = $res['Supplier_Name_Second'][$key] ?? '';
                $assettype->Invoice_No_Second = $res['Invoice_No_Second'][$key] ?? '';
                $assettype->QTY_Second = $res['QTY_Second'][$key] ?? '';
                $assettype->Organization_Second = $res['Organization_Second'][$key] ?? '';
                $assettype->Location_Second = $res['Location_Second'][$key] ?? '';
                $assettype->Use_By_Second = $res['Use_By_Second'][$key] ?? '';
                $assettype->Used_For = $res['Used_For'][$key] ?? '';
                $assettype->Other_Item_Details = $res['Other_Item_Details'][$key] ?? '';

                $assettype->save();
            }
        }
        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step9')->with('success', 'Successfull....');
        }
    }

    public function Power_House(Request $request)
    {
        if ($request->draft == 1) {
            return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
        } else {
            return redirect('FactoryCreater/step10')->with('success', 'Successfull....');
        }
    }


    public function Store(Request $request)
    {
        if ($request->draft != 1) {
            $request->validate([
                'Total_Rack' => 'required',
                'Rack_Capacity' => 'required',
                'Total_Bin' => 'required',
                'Total_Bin_Capacity' => 'required',
                'Rack_No' => 'required',
                'Rack_Capacities' => 'required',
                'Sub_Rack_No.*' => 'required',
                'Sub_Rack_Capacity.*' => 'required',
                'Bin_No.*' => 'required',
                'Bin_Capacity.*' => 'required',
                'Sub_Bin_No.*' => 'required',
                'Sub_Bin_Capacity.*' => 'required',
                'Total_Shelf' => 'required',
                'Total_Shelf_Capacity' => 'required',
                'Shelf_No.*' => 'required',
                'Shelf_Capacity.*' => 'required',
                'Sub_Shelf_No.*' => 'required',
                'Sub_Shelf_Capacity.*' => 'required',
            ]);
        }

        $factory_id = Session::get('factory_id');

        if ($request->edit != '') {
            $store = Factory_Store::where('factory_id', $request->edit)->first();
        } else {
            $store = new Factory_Store;
            $store->factory_id = $factory_id;
        }
        $store->Total_Rack = $request->Total_Rack;
        $store->Rack_Capacity = $request->Rack_Capacity;
        $store->Total_Bin = $request->Total_Bin;
        $store->Total_Bin_Capacity = $request->Total_Bin_Capacity;
        $store->Rack_No = $request->Rack_No;
        $store->Rack_Capacities = $request->Rack_Capacities;
        if ($request->draft == 1) {
            $store->status = 1;
        } else {
            $store->status = 0;
        }

        $store->save();

        $res = $request->input();

        foreach ($res['Sub_Rack_No'] as $key => $val) {
            $storesubrackID = isset($res['storesubrackID'][$key]) ? $res['storesubrackID'][$key] : '';
            if ($storesubrackID != '') {
                $SubRackNo = Factory_Stores_Sub_Rack_No::where('id', $storesubrackID)->update(['Sub_Rack_No' => $res['Sub_Rack_No'][$key] ?? '', 'Sub_Rack_Capacity' => $res['Sub_Rack_Capacity'][$key] ?? '']);
            } else {
                $SubRackNo = new Factory_Stores_Sub_Rack_No;
                $SubRackNo->factory_stores_id = $store->id;
                $SubRackNo->Sub_Rack_No = $res['Sub_Rack_No'][$key] ?? '';
                $SubRackNo->Sub_Rack_Capacity = $res['Sub_Rack_Capacity'][$key] ?? '';

                $SubRackNo->save();
            }

            if ($storesubrackID) {
                $storesubrack_ID = $storesubrackID;
            } else {
                $storesubrack_ID = $SubRackNo->id;
            }

            foreach ($res['Bin_No'][$key] as $key1 => $val) {
                $storebinID = isset($res['storebinID'][$key][$key1]) ? $res['storebinID'][$key][$key1] : '';
                if ($storebinID != '') {
                    $BinNo = Factory_Stores_Sub_Rack_No_Bin_No::where('id', $storebinID)->update(['Bin_No' => $res['Bin_No'][$key][$key1] ?? '', 'Bin_Capacity' => $res['Bin_Capacity'][$key][$key1] ?? '']);
                } else {
                    $BinNo = new Factory_Stores_Sub_Rack_No_Bin_No;
                    $BinNo->factory_stores_sub_rack_no_id = $storesubrack_ID;
                    $BinNo->Bin_No = $res['Bin_No'][$key][$key1] ?? '';
                    $BinNo->Bin_Capacity = $res['Bin_Capacity'][$key][$key1] ?? '';

                    $BinNo->save();
                }

                if ($storebinID) {
                    $storebin_ID = $storebinID;
                } else {
                    $storebin_ID = $BinNo->id;
                }

                foreach ($res['Sub_Bin_No'][$key][$key1] as $key2 => $val) {
                    $storesubbinID = isset($res['storesubbinID'][$key][$key1][$key2]) ? $res['storesubbinID'][$key][$key1][$key2] : '';
                    if ($storesubbinID != '') {
                        $SubBinNo = Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No::where('id', $storesubbinID)->update(['Sub_Bin_No' => $res['Sub_Bin_No'][$key][$key1][$key2] ?? '', 'Sub_Bin_Capacity' => $res['Sub_Bin_Capacity'][$key][$key1][$key2] ?? '']);
                    } else {
                        $SubBinNo = new Factory_Stores_Sub_Rack_No_Bin_No_Sub_Bin_No;
                        $SubBinNo->factory_stores_sub_rack_no_bin_no_id = $storebin_ID;
                        $SubBinNo->Sub_Bin_No = $res['Sub_Bin_No'][$key][$key1][$key2] ?? '';
                        $SubBinNo->Sub_Bin_Capacity = $res['Sub_Bin_Capacity'][$key][$key1][$key2] ?? '';

                        $SubBinNo->save();
                    }
                }
            }
        }

        if ($request->shelfID != '') {
            $ShelfDetails = Factory_Shelf_Details::where('id', $request->shelfID)->first();
        } else {
            $ShelfDetails = new Factory_Shelf_Details;
            $ShelfDetails->factory_id = $factory_id;
        }

        $ShelfDetails->Total_Shelf = $request->Total_Shelf;
        $ShelfDetails->Total_Shelf_Capacity = $request->Total_Shelf_Capacity;
        $ShelfDetails->Remark = $request->Remark;

        if ($request->draft == 1) {
            $ShelfDetails->status = 1;
        } else {
            $ShelfDetails->status = 0;
        }

        $ShelfDetails->save();


        foreach ($res['Shelf_No'] as $key => $val) {
            $shelfNoID = isset($res['shelfNoID'][$key]) ? $res['shelfNoID'][$key] : '';
            if ($shelfNoID != '') {
                $shelfno = Factory_Shelf_Details_Shelf_No::where('id', $shelfNoID)->update(['Shelf_No' => $res['Shelf_No'][$key] ?? '', 'Shelf_Capacity' => $res['Shelf_Capacity'][$key] ?? '']);
            } else {
                $shelfno = new Factory_Shelf_Details_Shelf_No;
                $shelfno->factory_shelf_details_id = $ShelfDetails->id;
                $shelfno->Shelf_No = $res['Shelf_No'][$key] ?? '';
                $shelfno->Shelf_Capacity = $res['Shelf_Capacity'][$key] ?? '';

                $shelfno->save();
            }

            if ($shelfNoID) {
                $shelfNo_ID = $shelfNoID;
            } else {
                $shelfNo_ID = $shelfno->id;
            }

            foreach ($res['Sub_Shelf_No'][$key] as $key1 => $val) {
                $SubshelfID = isset($res['SubshelfID'][$key][$key1]) ? $res['SubshelfID'][$key][$key1] : '';
                if ($SubshelfID != '') {
                    $shelfno = Factory_Shelf_Details_Shelf_No_Sub_Shelf_No::where('id', $SubshelfID)->update(['Sub_Shelf_No' => $res['Sub_Shelf_No'][$key][$key1] ?? '', 'Sub_Shelf_Capacity' => $res['Sub_Shelf_Capacity'][$key][$key1] ?? '']);
                } else {
                    $subshelfno = new Factory_Shelf_Details_Shelf_No_Sub_Shelf_No;
                    $subshelfno->factory_shelf_details_shelf_no_id = $shelfNo_ID;
                    $subshelfno->Sub_Shelf_No = $res['Sub_Shelf_No'][$key][$key1] ?? '';
                    $subshelfno->Sub_Shelf_Capacity = $res['Sub_Shelf_Capacity'][$key][$key1] ?? '';

                    $subshelfno->save();
                }
            }
        }

        $Approve_step = Factory_Address_Detail::where('id', $factory_id)->update(['Approve_Step' => 1]);

        if ($request->shelfID != '' && !isset($request->draft)) {
            $dataStatus = Factory_Address_Detail::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = Factory_Address_Detail::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = Factory_Approve::where('factory_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new Factory_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->factory_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('FactoryCreater/List')->with('success', 'Saved in draft');
    }
}
