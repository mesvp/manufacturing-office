<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Gatepass\{Master_employee_names,Master_request_type,Master_person_to_meets,Master_departments,Master_request_through,Master_contact_person};



class MasterGatepassController extends Controller
{    
    public function employee_name($id = null)
    {
        $employee_name = Master_employee_names::all();
        $edit = Master_employee_names::find($id);

        return view('Master.Gatepass.employee_name', ['employee_name' => $employee_name, 'edit' => $edit]);
    }

    public function employee_name_store(Request $request)
    {
        $duplicate = Master_employee_names::where('employee_name', $request->employee_name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $employee_name = Master_employee_names::where('id', $request->edit)->first();
            } else {
                $employee_name = new Master_employee_names;
            }

            $employee_name->employee_name = $request->employee_name;
            $employee_name->save();
        } else {
            return redirect('Master/employee_name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/employee_name')->with('success', 'Successfully...');
    }

    public function delete_employee_name($id)
    {
        Master_employee_names::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function request_type($id = null)
    {
        $request_type = Master_request_type::all();
        $edit = Master_request_type::find($id);

        return view('Master.Gatepass.request_type', ['request_type' => $request_type, 'edit' => $edit]);
    }

    public function request_type_store(Request $request)
    {
        $duplicate = Master_request_type::where('request_type', $request->request_type)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $request_type = Master_request_type::where('id', $request->edit)->first();
            } else {
                $request_type = new Master_request_type;
            }

            $request_type->request_type = $request->request_type;
            $request_type->save();
        } else {
            return redirect('Master/request_type')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/request_type')->with('success', 'Successfully...');
    }

    public function delete_request_type($id)
    {
        Master_request_type::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function person_to_meet($id = null)
    {
        $person_to_meet = Master_person_to_meets::all();
        $edit = Master_person_to_meets::find($id);

        return view('Master.Gatepass.person_to_meet', ['person_to_meet' => $person_to_meet, 'edit' => $edit]);
    }

    public function person_to_meet_store(Request $request)
    {
        $duplicate = Master_person_to_meets::where('person_to_meet', $request->person_to_meet)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $person_to_meet = Master_person_to_meets::where('id', $request->edit)->first();
            } else {
                $person_to_meet = new Master_person_to_meets;
            }

            $person_to_meet->person_to_meet = $request->person_to_meet;
            $person_to_meet->save();
        } else {
            return redirect('Master/person_to_meet')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/person_to_meet')->with('success', 'Successfully...');
    }

    public function delete_person_to_meet($id)
    {
        Master_person_to_meets::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function request_through($id = null)
    {
        $request_through = Master_request_through::all();
        $edit = Master_request_through::find($id);

        return view('Master.Gatepass.request_through', ['request_through' => $request_through, 'edit' => $edit]);
    }

    public function request_through_store(Request $request)
    {
        $duplicate = Master_request_through::where('request_through', $request->request_through)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $request_through = Master_request_through::where('id', $request->edit)->first();
            } else {
                $request_through = new Master_request_through;
            }

            $request_through->request_through = $request->request_through;
            $request_through->save();
        } else {
            return redirect('Master/request_through')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/request_through')->with('success', 'Successfully...');
    }

    public function delete_request_through($id)
    {
        Master_request_through::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function department($id = null)
    {
        $department = Master_departments::all();
        $edit = Master_departments::find($id);

        return view('Master.Gatepass.department', ['department' => $department, 'edit' => $edit]);
    }

    public function department_store(Request $request)
    {
        $duplicate = Master_departments::where('department', $request->department)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $department = Master_departments::where('id', $request->edit)->first();
            } else {
                $department = new Master_departments;
            }

            $department->department = $request->department;
            $department->save();
        } else {
            return redirect('Master/department')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/department')->with('success', 'Successfully...');
    }

    public function delete_department($id)
    {
        Master_departments::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function contact_person($id = null)
    {
        $contact_person = Master_contact_person::all();
        $edit = Master_contact_person::find($id);

        return view('Master.Gatepass.contact_person', ['contact_person' => $contact_person, 'edit' => $edit]);
    }

    public function contact_person_store(Request $request)
    {
        $duplicate = Master_contact_person::where('contact_person', $request->contact_person)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $contact_person = Master_contact_person::where('id', $request->edit)->first();
            } else {
                $contact_person = new Master_contact_person;
            }

            $contact_person->contact_person = $request->contact_person;
            $contact_person->save();
        } else {
            return redirect('Master/contact_person')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/contact_person')->with('success', 'Successfully...');
    }

    public function delete_contact_person($id)
    {
        Master_contact_person::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
