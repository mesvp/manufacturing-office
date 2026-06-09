<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use Hash;



class EmployeeController extends Controller
{


    public function index()
    {

        $userdata = Admin::all();
        return view('admin.employee.index', ['user' => $userdata]);
    }


    public function add_employee()
    {
        $role = Role::all();
        return view('admin.employee.add_employee', ['role' => $role]);
    }
    public function add_employee_data(Request $request)
    {
        $validated = $request->validate([           
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'aadhar' => 'required',
            'address' => 'required',
            'pan' => 'required',
            'joining_date' => 'required',
            'bank_name' => 'required',
            'account_no' => 'required',
            'ifsc' => 'required',
            'micr_code' => 'required',
            'password' => 'required',
        ]);
        $res = new Admin;
        $res->name = $request->name;
        $res->email = $request->email;
        $res->role = $request->role;
        $res->status = 0;
        $res->votar_id = $request->votar_id;
        $res->mobile = $request->mobile;
        $res->aadhar = $request->aadhar;
        $res->address = $request->address;
        $res->pan = $request->pan;
        $res->joining_date = $request->joining_date;
        $res->exit_date = $request->exit_date;
        $res->bank_name = $request->bank_name;
        $res->account_no = $request->account_no;
        $res->ifsc = $request->ifsc;
        $res->micr_code = $request->micr_code;
        $res->password = Hash::make($request->password);
        $res->save();
        if ($res) {
            return redirect('admin/employees')->withSuccess("Added Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }

    public function edit_emp($id)
    {
        $edit = Admin::find($id);
        $role = Role::all();

        return view('admin.employee.edit_employee', ['editt' => $edit, 'role' => $role]);
    }

    public function edit_employee(Request $res)
    {
        $validated = $res->validate([           
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'aadhar' => 'required',
            'address' => 'required',
            'pan' => 'required',
            'joining_date' => 'required',
            'bank_name' => 'required',
            'account_no' => 'required',
            'ifsc' => 'required',
            'micr_code' => 'required',           
        ]);
        if ($res->id) {
            $edit = Admin::find($res->id);
        } else {
            $edit = new Admin;
        }
        $edit->name = $res->name;
        $edit->email = $res->email;
        $edit->role = $res->role;
        $edit->votar_id = $res->votar_id;
        $edit->mobile = $res->mobile;
        $edit->aadhar = $res->aadhar;
        $edit->address = $res->address;
        $edit->pan = $res->pan;
        $edit->joining_date = $res->joining_date;
        $edit->exit_date = $res->exit_date;
        $edit->bank_name = $res->bank_name;
        $edit->account_no = $res->account_no;
        $edit->ifsc = $res->ifsc;
        $edit->micr_code = $res->micr_code;
        if ($res->password != '') {
            $edit->password = Hash::make($res->password);
        }
        $edit->save();

        if ($edit) {
            return redirect('admin/employees')->withSuccess("Added Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }

    }


    public function destroy($id)
    {
        $res = Admin::findOrfail(decrypt($id));
        if ($res->delete()) {        
            return back()->withSuccess("Deleted Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }  


    public function userblock(Request $request, $id, $status)
    {

        $res = Admin::where('id',decrypt($id));
        if ($status == 0) {
            if ($res->update(['status' => 0])) {
                return back()->withSuccess("Unblocked Successfully...");
            } else {
                return back()->withError("Something went wrong!");
            }
        } else {
            if ($res->update(['status' => 1])) {
                return back()->withSuccess("Blocked Successfully...");
            } else {
                return back()->withError("Something went wrong!");
            }
        }
    }

}
