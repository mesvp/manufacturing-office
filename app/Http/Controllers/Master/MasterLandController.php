<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\landbuilding\{Master_Land_Building, Master_Boundary_Height, Master_Boundary_Type, Master_Boundary_Width, Master_Building_Area, Master_Building_Type, Master_Cover_Area, Master_Gate, Master_Land_Area, Master_Open_Area, Master_Window};


class MasterLandController extends Controller
{
    public function land_type($id = null)
    {
        $landtype = Master_Land_Building::all();
        $edit = Master_Land_Building::find($id);

        return view('Master.Land.LandType', ['landtype' => $landtype, 'edit' => $edit]);
    }

    public function land_type_store(Request $request)
    {
        $duplicate = Master_Land_Building::where('land_type', $request->land_type)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $landtype = Master_Land_Building::find($request->edit);
            } else {
                $landtype = new Master_Land_Building;
            }
            $landtype->land_type = $request->land_type;
            $landtype->save();
        } else {
            return redirect('Master/land_type')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/land_type')->with('success', 'Successfull...');
    }

    public function delete_landtype($id)
    {
        Master_Land_Building::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BoundaryHeight($id = null)
    {
        $boundryheight = Master_Boundary_Height::all();
        $edit = Master_Boundary_Height::find($id);

        return view('Master.Land.BoundaryHeight', ['boundryheight' => $boundryheight, 'edit' => $edit]);
    }

    public function BoundaryHeight_store(Request $request)
    {
        $duplicate = Master_Boundary_Height::where('Boundary_Height', $request->Boundary_Height)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $boundryheight = Master_Boundary_Height::find($request->edit);
            } else {
                $boundryheight = new Master_Boundary_Height;
            }
            $boundryheight->Boundary_Height = $request->Boundary_Height;
            $boundryheight->save();
        } else {
            return redirect('Master/BoundaryHeight')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BoundaryHeight')->with('success', 'Successfull...');
    }

    public function delete_BoundaryHeight($id)
    {
        Master_Boundary_Height::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BoundaryType($id = null)
    {
        $BoundaryType = Master_Boundary_Type::all();
        $edit = Master_Boundary_Type::find($id);

        return view('Master.Land.BoundaryType', ['BoundaryType' => $BoundaryType, 'edit' => $edit]);
    }

    public function BoundaryType_store(Request $request)
    {
        $duplicate = Master_Boundary_Type::where('Boundary_Type', $request->Boundary_Type)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $BoundaryType = Master_Boundary_Type::find($request->edit);
            } else {
                $BoundaryType = new Master_Boundary_Type;
            }
            $BoundaryType->Boundary_Type = $request->Boundary_Type;
            $BoundaryType->save();
        } else {
            return redirect('Master/BoundaryType')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BoundaryType')->with('success', 'Successfull...');
    }

    public function delete_BoundaryType($id)
    {
        Master_Boundary_Type::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BoundaryWidth($id = null)
    {
        $BoundaryWidth = Master_Boundary_Width::all();
        $edit = Master_Boundary_Width::find($id);

        return view('Master.Land.BoundaryWidth', ['BoundaryWidth' => $BoundaryWidth, 'edit' => $edit]);
    }

    public function BoundaryWidth_store(Request $request)
    {
        $duplicate = Master_Boundary_Width::where('Boundary_Width', $request->Boundary_Width)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $BoundaryWidth = Master_Boundary_Width::find($request->edit);
            } else {
                $BoundaryWidth = new Master_Boundary_Width;
            }
            $BoundaryWidth->Boundary_Width = $request->Boundary_Width;
            $BoundaryWidth->save();
        } else {
            return redirect('Master/BoundaryWidth')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BoundaryWidth')->with('success', 'Successfull...');
    }

    public function delete_BoundaryWidth($id)
    {
        Master_Boundary_Width::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BuildingArea($id = null)
    {
        $BuildingArea = Master_Building_Area::all();
        $edit = Master_Building_Area::find($id);

        return view('Master.Land.BuildingArea', ['BuildingArea' => $BuildingArea, 'edit' => $edit]);
    }

    public function BuildingArea_store(Request $request)
    {
        $duplicate = Master_Building_Area::where('Building_Area', $request->Building_Area)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $BuildingArea = Master_Building_Area::find($request->edit);
            } else {
                $BuildingArea = new Master_Building_Area;
            }
            $BuildingArea->Building_Area = $request->Building_Area;
            $BuildingArea->save();
        } else {
            return redirect('Master/BuildingArea')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BuildingArea')->with('success', 'Successfull...');
    }

    public function delete_BuildingArea($id)
    {
        Master_Building_Area::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BuildingType($id = null)
    {
        $BuildingType = Master_Building_Type::all();
        $edit = Master_Building_Type::find($id);

        return view('Master.Land.BuildingType', ['BuildingType' => $BuildingType, 'edit' => $edit]);
    }

    public function BuildingType_store(Request $request)
    {
        $duplicate = Master_Building_Type::where('Building_Type', $request->Building_Type)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $BuildingType = Master_Building_Type::find($request->edit);
            } else {
                $BuildingType = new Master_Building_Type;
            }
            $BuildingType->Building_Type = $request->Building_Type;
            $BuildingType->save();
        } else {
            return redirect('Master/BuildingType')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BuildingType')->with('success', 'Successfull...');
    }

    public function delete_BuildingType($id)
    {
        Master_Building_Type::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function CoverArea($id = null)
    {
        $CoverArea = Master_Cover_Area::all();
        $edit = Master_Cover_Area::find($id);

        return view('Master.Land.CoverArea', ['CoverArea' => $CoverArea, 'edit' => $edit]);
    }

    public function CoverArea_store(Request $request)
    {
        $duplicate = Master_Cover_Area::where('Cover_Area', $request->Cover_Area)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $CoverArea = Master_Cover_Area::find($request->edit);
            } else {
                $CoverArea = new Master_Cover_Area;
            }
            $CoverArea->Cover_Area = $request->Cover_Area;
            $CoverArea->save();
        } else {
            return redirect('Master/CoverArea')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/CoverArea')->with('success', 'Successfull...');
    }

    public function delete_CoverArea($id)
    {
        Master_Cover_Area::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Gate($id = null)
    {
        $Gate = Master_Gate::all();
        $edit = Master_Gate::find($id);

        return view('Master.Land.Gate', ['Gate' => $Gate, 'edit' => $edit]);
    }

    public function Gate_store(Request $request)
    {
        $duplicate = Master_Gate::where('Gate', $request->Gate)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Gate = Master_Gate::find($request->edit);
            } else {
                $Gate = new Master_Gate;
            }
            $Gate->Gate = $request->Gate;
            $Gate->save();
        } else {
            return redirect('Master/Gate')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Gate')->with('success', 'Successfull...');
    }

    public function delete_Gate($id)
    {
        Master_Gate::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function LandArea($id = null)
    {
        $LandArea = Master_Land_Area::all();
        $edit = Master_Land_Area::find($id);

        return view('Master.Land.LandArea', ['LandArea' => $LandArea, 'edit' => $edit]);
    }

    public function LandArea_store(Request $request)
    {
        $duplicate = Master_Land_Area::where('Land_Area', $request->Land_Area)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $LandArea = Master_Land_Area::find($request->edit);
            } else {
                $LandArea = new Master_Land_Area;
            }
            $LandArea->Land_Area = $request->Land_Area;
            $LandArea->save();
        } else {
            return redirect('Master/LandArea')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/LandArea')->with('success', 'Successfull...');
    }

    public function delete_LandArea($id)
    {
        Master_Land_Area::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function OpenArea($id = null)
    {
        $OpenArea = Master_Open_Area::all();
        $edit = Master_Open_Area::find($id);

        return view('Master.Land.OpenArea', ['OpenArea' => $OpenArea, 'edit' => $edit]);
    }

    public function OpenArea_store(Request $request)
    {
        $duplicate = Master_Open_Area::where('Open_Area', $request->Open_Area)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $OpenArea = Master_Open_Area::find($request->edit);
            } else {
                $OpenArea = new Master_Open_Area;
            }
            $OpenArea->Open_Area = $request->Open_Area;
            $OpenArea->save();
        } else {
            return redirect('Master/OpenArea')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/OpenArea')->with('success', 'Successfull...');
    }

    public function delete_OpenArea($id)
    {
        Master_Open_Area::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Window($id = null)
    {
        $Window = Master_Window::all();
        $edit = Master_Window::find($id);

        return view('Master.Land.Window', ['Window' => $Window, 'edit' => $edit]);
    }

    public function Window_store(Request $request)
    {
        $duplicate = Master_Window::where('Window', $request->Window)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Window = Master_Window::find($request->edit);
            } else {
                $Window = new Master_Window;
            }
            $Window->Window = $request->Window;
            $Window->save();
        } else {
            return redirect('Master/Window')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Window')->with('success', 'Successfull...');
    }

    public function delete_Window($id)
    {
        Master_Window::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
