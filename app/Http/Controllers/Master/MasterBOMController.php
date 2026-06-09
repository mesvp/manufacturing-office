<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\BOM\{Master_Code,Master_Color,Master_Consumbles,Master_GST_Percentage,Master_Management_Expenses,Master_Material,Master_Services,Master_Machine_Specification};

class MasterBOMController extends Controller
{    
    public function Code($id = null)
    {
        $Code = Master_Code::all();
        $edit = Master_Code::find($id);

        return view('Master.BOM.Code', ['Code' => $Code, 'edit' => $edit]);
    }

    public function Code_store(Request $request)
    {
        $duplicate = Master_Code::where('Code', $request->Code)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Code = Master_Code::where('id', $request->edit)->first();
            } else {
                $Code = new Master_Code;
            }

            $Code->Code = $request->Code;
            $Code->save();
        } else {
            return redirect('Master/Code')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Code')->with('success', 'Successfully...');
    }

    public function delete_Code($id)
    {
        Master_Code::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Color($id = null)
    {
        $Color = Master_Color::all();
        $edit = Master_Color::find($id);

        return view('Master.BOM.Color', ['Color' => $Color, 'edit' => $edit]);
    }

    public function Color_store(Request $request)
    {
        $duplicate = Master_Color::where('Color', $request->Color)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Color = Master_Color::where('id', $request->edit)->first();
            } else {
                $Color = new Master_Color;
            }

            $Color->Color = $request->Color;
            $Color->save();
        } else {
            return redirect('Master/Color')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Color')->with('success', 'Successfully...');
    }

    public function delete_Color($id)
    {
        Master_Color::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Consumbles($id = null)
    {
        $Consumbles = Master_Consumbles::all();
        $edit = Master_Consumbles::find($id);

        return view('Master.BOM.Consumbles', ['Consumbles' => $Consumbles, 'edit' => $edit]);
    }

    public function Consumbles_store(Request $request)
    {
        $duplicate = Master_Consumbles::where('Consumbles', $request->Consumbles)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Consumbles = Master_Consumbles::where('id', $request->edit)->first();
            } else {
                $Consumbles = new Master_Consumbles;
            }

            $Consumbles->Consumbles = $request->Consumbles;
            $Consumbles->save();
        } else {
            return redirect('Master/Consumbles')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Consumbles')->with('success', 'Successfully...');
    }

    public function delete_Consumbles($id)
    {
        Master_Consumbles::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function GST_Percentage($id = null)
    {
        $GST_Percentage = Master_GST_Percentage::all();
        $edit = Master_GST_Percentage::find($id);

        return view('Master.BOM.GST_Percentage', ['GST_Percentage' => $GST_Percentage, 'edit' => $edit]);
    }

    public function GST_Percentage_store(Request $request)
    {
        $duplicate = Master_GST_Percentage::where('GST_Percentage', $request->GST_Percentage)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $GST_Percentage = Master_GST_Percentage::where('id', $request->edit)->first();
            } else {
                $GST_Percentage = new Master_GST_Percentage;
            }

            $GST_Percentage->GST_Percentage = $request->GST_Percentage;
            $GST_Percentage->save();
        } else {
            return redirect('Master/GST_Percentage')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/GST_Percentage')->with('success', 'Successfully...');
    }

    public function delete_GST_Percentage($id)
    {
        Master_GST_Percentage::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Management_Expenses($id = null)
    {
        $Management_Expenses = Master_Management_Expenses::all();
        $edit = Master_Management_Expenses::find($id);

        return view('Master.BOM.Management_Expenses', ['Management_Expenses' => $Management_Expenses, 'edit' => $edit]);
    }

    public function Management_Expenses_store(Request $request)
    {
        $duplicate = Master_Management_Expenses::where('Management_Expenses', $request->Management_Expenses)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Management_Expenses = Master_Management_Expenses::where('id', $request->edit)->first();
            } else {
                $Management_Expenses = new Master_Management_Expenses;
            }

            $Management_Expenses->Management_Expenses = $request->Management_Expenses;
            $Management_Expenses->save();
        } else {
            return redirect('Master/Management_Expenses')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Management_Expenses')->with('success', 'Successfully...');
    }

    public function delete_Management_Expenses($id)
    {
        Master_Management_Expenses::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Material($id = null)
    {
        $Material = Master_Material::all();
        $edit = Master_Material::find($id);

        return view('Master.BOM.Material', ['Material' => $Material, 'edit' => $edit]);
    }

    public function Material_store(Request $request)
    {
        $duplicate = Master_Material::where('Material', $request->Material)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Material = Master_Material::where('id', $request->edit)->first();
            } else {
                $Material = new Master_Material;
            }

            $Material->Material = $request->Material;
            $Material->save();
        } else {
            return redirect('Master/Material')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Material')->with('success', 'Successfully...');
    }

    public function delete_Material($id)
    {
        Master_Material::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Services($id = null)
    {
        $Services = Master_Services::all();
        $edit = Master_Services::find($id);

        return view('Master.BOM.Services', ['Services' => $Services, 'edit' => $edit]);
    }

    public function Services_store(Request $request)
    {
        $duplicate = Master_Services::where('Services', $request->Services)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Services = Master_Services::where('id', $request->edit)->first();
            } else {
                $Services = new Master_Services;
            }

            $Services->Services = $request->Services;
            $Services->save();
        } else {
            return redirect('Master/Services')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Services')->with('success', 'Successfully...');
    }

    public function delete_Services($id)
    {
        Master_Services::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Machine_Specification($id = null)
    {
        $Machine_Specification = Master_Machine_Specification::all();
        $edit = Master_Machine_Specification::find($id);

        return view('Master.BOM.Machine_Specification', ['Machine_Specification' => $Machine_Specification, 'edit' => $edit]);
    }

    public function Machine_Specification_store(Request $request)
    {
        $duplicate = Master_Machine_Specification::where('Machine_Specification', $request->Machine_Specification)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Machine_Specification = Master_Machine_Specification::where('id', $request->edit)->first();
            } else {
                $Machine_Specification = new Master_Machine_Specification;
            }

            $Machine_Specification->Machine_Specification = $request->Machine_Specification;
            $Machine_Specification->save();
        } else {
            return redirect('Master/Machine_Specification')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Machine_Specification')->with('success', 'Successfully...');
    }

    public function delete_Machine_Specification($id)
    {
        Master_Machine_Specification::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
