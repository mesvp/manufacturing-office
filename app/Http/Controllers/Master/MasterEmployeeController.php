<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Employee_Department, Admin, Departments, Employee, Department_Assign};
use Hash;
use DB;


class MasterEmployeeController extends Controller
{
    public function Add_Employee($id = null)
    {
        $edit = Admin::find($id);
        $dep = [];
        if ($edit) {
            $employeeDepartments = Employee_Department::where('userID', $edit->id)->get();
            $dep = $employeeDepartments->pluck('Departments')->toArray();
        }

        $Departments = Departments::all();

        $emp =  Admin::where('role',1)->get();
        //$empname =  Admin::all();
        $empname = collect(DB::select("
            SELECT x.position, x.employee_id, y.id, y.fullname, d.dept_name, y.status 
            FROM hr_employee_service_register x  
            JOIN mstr_emp y ON x.emp_name = y.id  
            JOIN hr_department d ON x.department_id = d.id  
            WHERE x.ref_id = 0 

            UNION 

            SELECT x.position, x.employee_id, y.id, y.fullname, d.dept_name, y.status 
            FROM hr_employee_service_register x 
            JOIN mstr_emp y ON y.mstr_ref_id = x.ref_id 
            JOIN hr_department d ON x.department_id = d.id 
            WHERE x.ref_id <> 0

            ORDER BY id ASC
        "));
        foreach ($empname as $empItem) {

            if ($empItem->status == '1') {
                $empItem->status_class = 'text-success fw-bold';
                $empItem->status_text = '(Active Employee)';
            } elseif ($empItem->status == '2') {
                $empItem->status_class = 'text-danger fw-bold';
                $empItem->status_text = '(Blocked Employee)';
            } else {
                $empItem->status_class = 'text-danger fw-bold';
                $empItem->status_text = '(Inactive Employee / Resigned)';
            }

            if ($empItem->id == '0') {
                $empItem->details = "Other";
            } else {
                $empItem->details = $empItem->fullname;

                if (!empty($empItem->dept_name) && !empty($empItem->position)) {
                    $empItem->details .= " ({$empItem->dept_name}, {$empItem->position}, {$empItem->employee_id})";
                }
            }
        }
        $emp_arr = [];
        foreach ($emp as $val) {
            $val->Departmentss = Employee_Department::where('userID', $val->id)->get();
            $departss = [];
            foreach ($val->Departmentss as $valss) {
                $valss->depart = Departments::find($valss->Departments);

                $departss[] = $valss;
            }

            $val->departmentsss = $departss;

             $emp_arr[] = $val;
        }

        return view('Master.Add_Employee', ['edit' => $edit, 'Data' => $emp_arr,'empname'=>$empname, 'dep' => $dep, 'Departments' => $Departments]);
    }
    public function Remove_Employee($id) {
        $employee = Admin::find($id);
        if ($employee) {
            $employee->role = '';
            $employee->save();
            //return $employee;
            return redirect('Master/Add_Employee')->with('warning', 'Remove Successfully...');
        } 
    }

    public function store_Employee(Request $request)
    {
        //return $request->all();
        if ($request->edit != '') {
            $user = Admin::find($request->edit);
        } else {
            // $user = new Admin;
            // $user->role = 1;
            foreach ($request['employee_name'] as $key => $val) {
                $admin = Admin::find($val);
                if ($admin) {
                    $admin->role = 1;
                    $admin->save();
                } else {
                    return back();
                }
            }
           return redirect('Master/Add_Employee')->with('success', 'Updated Successfully...');
        }
        // $user->fullname = $request->name;
        // $user->uemail = $request->email;
        // if (isset($request->password) && $request->password != '') {
        //     $user->password = Hash::make($request->password);
        // }    
        //$user->save();

        Employee_Department::where('userID', $user->id)->delete();

        $res = $request->input();
        foreach ($res['Departments'] as $key => $val) {
            $emp = new Employee_Department;
            $emp->userID = $user->id;
            $emp->Departments = $request['Departments'][$key];
            $emp->save();
        }

        return redirect('Master/Add_Employee')->with('success', 'Added Successfully...');
    }

    public function delete_Employee($id)
    {
        Admin::find($id)->delete();
        Employee::where('userID', $id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Assign_Step($id = null)
    {
        $employeeName = Admin::where('role', 1)->get();
        $Departments = Departments::all();   
        $Depview = [];
        foreach ($Departments as $dpevalue) {
            $empoye_depart = Department_Assign::where('departments', $dpevalue->id)->get();
            $arr_forfetch = [];
            $arr_forfetch['inputer'] = [];
            $arr_forfetch['Approve_step1'] = [];
            $arr_forfetch['Approve_step2'] = [];
            $arr_forfetch['Approve_step3'] = [];
            foreach ($empoye_depart as $empvalue) {
                if ($empvalue->step == 0) {
                    array_push($arr_forfetch['inputer'], $empvalue->userID);
                } elseif ($empvalue->step == 1) {
                    array_push($arr_forfetch['Approve_step1'], $empvalue->userID);
                } elseif ($empvalue->step == 2) {
                    array_push($arr_forfetch['Approve_step2'], $empvalue->userID);
                } else {
                    array_push($arr_forfetch['Approve_step3'], $empvalue->userID);
                }
            }
            $Depview[$dpevalue->id] = $arr_forfetch;
        }

        return view('Master.Approve_Step_Assign', ['employeeName' => $employeeName, 'Departments' => $Departments, 'depview' => $Depview]);
    }

    public function store_Assign_Step(Request $request)
    {
        Department_Assign::truncate();
        $res = $request->input();
        foreach ($res as $key => $value) {
            if ($key != '_token') {
                $input_arr = [];
                foreach ($value as $key1 => $inputer_value) {
                    foreach ($inputer_value as $val) {
                        $input_arr['userID'] = $val;
                        $input_arr['departments'] = $key1;
                        $input_arr['ex_type'] = $key == 'inputer' ? 1 : 2;
                        if ($key == 'inputer') {
                            $input_arr['step'] = 0;
                        } elseif ($key == 'Approve_step1') {
                            $input_arr['step'] = 1;
                        } elseif ($key == 'Approve_step2') {
                            $input_arr['step'] = 2;
                        } elseif ($key == 'Approve_step3') {
                            $input_arr['step'] = 3;
                        } else {
                            return redirect()->back()->with('error', 'Check Data !');
                        }

                        Department_Assign::insert($input_arr);
                    }
                }
            }
        }   

        return redirect('Master/Assign_Step')->with('success', 'Added Successfully...');
    }
}
