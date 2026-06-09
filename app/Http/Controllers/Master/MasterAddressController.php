<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{City, Country, State, Employee_Type};
use App\Models\FactoryCreater\{Factory_Organisation, Factory_Name_Of_Unit};


class MasterAddressController extends Controller
{
    public function country($id = null)
    {
        $country = Country::all();
        $edit = Country::find($id);

        return view('Master.Adress.Country', ['country' => $country, 'edit' => $edit]);
    }

    public function country_store(Request $request)
    {
        $duplicate = Country::where('name', $request->name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $country = Country::find($request->edit);
            } else {
                $country = new Country;
            }
            $country->name = $request->name;
            $country->save();
        } else {
            return redirect('Master/country')->with('errors', 'can not save duplicate data....');
        }
        return redirect('Master/country')->with('success', 'Added Successfully...');
    }

    public function delete_country($id)
    {
        Country::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function state($id = null)
    {
        $country = Country::all();
        $state = State::all();
        $state_arr = array();
        foreach ($state as $val) {
            $val->country = Country::where('id', $val->country_id)->first();
            array_push($state_arr, $val);
        }

        $edit = State::find($id);


        return view('Master.Adress.State', ['country' => $country, 'state' => $state_arr, 'edit' => $edit]);
    }

    public function state_store(Request $request)
    {
        $duplicate = State::where('name', $request->name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $state = State::find($request->edit);
            } else {
                $state = new State;
            }
            $state->country_id = $request->country_id;
            $state->name = $request->name;
            $state->save();
        } else {
            return redirect('Master/state')->with('errors', 'can not save duplicate data....');
        }
        return redirect('Master/state')->with('success', 'Added Successfully...');
    }

    public function delete_state($id)
    {
        State::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function district($id = null)
    {
        $country = Country::all();
        $state = State::all();
        $district = City::all();
        $district_arr = array();
        foreach ($district as $val) {
            $val->state = State::find($val->state_id);
            if (!empty($val->state)) {
                $val->country = Country::where('id', $val->state->country_id)->first();
                array_push($district_arr, $val);
            }
        }

        $edit = City::find($id);
        $contry = '';
        if (isset($edit->state_id)) {
            $contry = State::where('id', $edit->state_id)->first();
        }

        return view('Master.Adress.District', ['country' => $country, 'state' => $state, 'district' => $district_arr, 'edit' => $edit, 'contry' => $contry]);
    }

    public function district_store(Request $request)
    {
        $duplicate = City::where('city', $request->city)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $district = City::find($request->edit);
            } else {
                $district = new City;
            }
            $district->state_id = $request->state_id;
            $district->city = $request->city;
            $district->save();
        } else {
            return redirect('Master/district')->with('errors', 'can not save duplicate data....');
        }
        return redirect('Master/district')->with('success', 'Added Successfully...');
    }

    public function delete_district($id)
    {
        City::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function organization($id = null)
    {
        $organization = Factory_Organisation::all();
        $edit = Factory_Organisation::find($id);

        return view('Master.Adress.Organization', ['organization' => $organization, 'edit' => $edit]);
    }

    public function organization_store(Request $request)
    {
        $duplicate = Factory_Organisation::where('organization', $request->organization)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $organization = Factory_Organisation::find($request->edit);
            } else {
                $organization = new Factory_Organisation;
            }
            $organization->organization = $request->organization;
            $organization->save();
        } else {
            return redirect('Master/organization')->with('errors', 'can not save duplicate data....');
        }
        return redirect('Master/organization')->with('success', 'Added Successfully...');
    }

    public function delete_organization($id)
    {
        Factory_Organisation::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function name_of_unit($id = null)
    {
        $nameofunit = Factory_Name_Of_Unit::all();
        $edit = Factory_Name_Of_Unit::find($id);

        return view('Master.Adress.Name_Of_Unit', ['nameofunit' => $nameofunit, 'edit' => $edit]);
    }

    public function name_of_unit_store(Request $request)
    {
        $duplicate = Factory_Name_Of_Unit::where('name_of_unit', $request->name_of_unit)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $nameofunit = Factory_Name_Of_Unit::find($request->edit);
            } else {
                $nameofunit = new Factory_Name_Of_Unit;
            }
            $nameofunit->name_of_unit = $request->name_of_unit;
            $nameofunit->save();
        } else {
            return redirect('Master/name_of_unit')->with('errors', 'can not save duplicate data....');
        }
        return redirect('Master/name_of_unit')->with('success', 'Added Successfully...');
    }

    public function delete_nameofunit($id)
    {
        Factory_Name_Of_Unit::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Employee_Type($id = null)
    {
        $Employee_Type = Employee_Type::all();
        $edit = Employee_Type::find($id);

        return view('Master.Adress.Employee_Type', ['Employee_Type' => $Employee_Type, 'edit' => $edit]);
    }

    public function Employee_Type_store(Request $request)
    {

        if ($request->edit != '') {
            $Employee_Type = Employee_Type::find($request->edit);
        } else {
            $Employee_Type = new Employee_Type;
        }
        $Employee_Type->Employee_Type = $request->Employee_Type;
        $Employee_Type->Executor_Type = $request->Executor_Type;
        $Employee_Type->save();

        return redirect('Master/Employee_Type')->with('success', 'Added Successfully...');
    }

    public function delete_Employee_Type($id)
    {
        Employee_Type::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
