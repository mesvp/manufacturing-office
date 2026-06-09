<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FactoryCreater\{Factory_Product, Factory_Sub_Product, Factory_Sub_Sub_Product, Factory_Uom};
use App\Models\Master\Master_Plant_Machinery;
use App\Models\Master\Plant\{Master_Accessories, Master_Duration, Master_Machine_Code, Master_Machine_Name, Master_Make_Model, Master_Production_Capacity, Master_Specification, Master_Warranty, Master_Manufacturing_unit, Master_BU, Master_Quality_Check, Master_category, Master_Customer_Name, Master_Company_Name, Master_Work_Order_Status};


class MasterPlantController extends Controller
{
    public function plant_name($id = null)
    {
        $plantname = Master_Plant_Machinery::all();
        $edit = Master_Plant_Machinery::find($id);

        return view('Master.Plant.PlantName', ['plantname' => $plantname, 'edit' => $edit]);
    }

    public function plant_name_store(Request $request)
    {
        $duplicate = Master_Plant_Machinery::where('plant_name', $request->plant_name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $plant = Master_Plant_Machinery::where('id', $request->edit)->first();
            } else {
                $plant = new Master_Plant_Machinery;
            }
            $plant->plant_name = $request->plant_name;
            $plant->save();
        } else {
            return redirect('Master/plant_name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/plant_name')->with('success', 'Added Successfully...');
    }

    public function delete_plantname($id)
    {
        Master_Plant_Machinery::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function product($id = null)
    {
        $product =  Factory_Product::all();
        $edit = Factory_Product::find($id);

        return view('Master.Plant.Product', ['product' => $product, 'edit' => $edit]);
    }

    public function product_store(Request $request)
    {
        $duplicate = Factory_Product::where('product', $request->product)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $product = Factory_Product::where('id', $request->edit)->first();
            } else {
                $product = new Factory_Product;
            }
            $product->product = $request->product;
            $product->save();
        } else {
            return redirect('Master/product')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/product')->with('success', 'Added Successfully...');
    }

    public function delete_product($id)
    {
        Factory_Product::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Subproduct($id = null)
    {
        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subproduct_arr = array();
        foreach ($subproduct as $val) {
            $val->product = Factory_Product::find($val->product_id);
            array_push($subproduct_arr, $val);
        }

        $edit = Factory_Sub_Product::find($id);

        return view('Master.Plant.SubProduct', ['product' => $product, 'subproduct' => $subproduct_arr, 'edit' => $edit]);
    }

    public function Subproduct_store(Request $request)
    {
        $duplicate = Factory_Sub_Product::where('sub_product', $request->sub_product)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $subproduct = Factory_Sub_Product::where('id', $request->edit)->first();
            } else {
                $subproduct = new Factory_Sub_Product;
            }
            $subproduct->product_id = $request->product_id;
            $subproduct->sub_product = $request->sub_product;
            $subproduct->save();
        } else {
            return redirect('Master/Subproduct')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Subproduct')->with('success', 'Added Successfully...');
    }

    public function delete_Subproduct($id)
    {
        Factory_Sub_Product::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function subsubproduct($id = null)
    {
        $product = Factory_Product::all();
        $subproduct = Factory_Sub_Product::all();
        $subsubproduct = Factory_Sub_Sub_Product::all();
        $subsubproduct_arr = array();
        foreach ($subsubproduct as $val) {
            $val->subproduct = Factory_Sub_Product::find($val->sub_product_id);
            if (!empty($val->subproduct)) {
                $val->product = Factory_Product::find($val->subproduct->product_id);
            }

            array_push($subsubproduct_arr, $val);
        }
        $edit = Factory_Sub_Sub_Product::find($id);
        $editsub = '';
        if (isset($edit->id)) {
            $editsub = Factory_Sub_Product::where('id', $edit->sub_product_id)->first();
        }

        return view('Master.Plant.SubSubProduct', ['product' => $product, 'subproduct' => $subproduct, 'subsubproduct' => $subsubproduct_arr, 'edit' => $edit, 'editsub' => $editsub]);
    }

    public function subsubproduct_store(Request $request)
    {
        $duplicate = Factory_Sub_Sub_Product::where('sub_sub_product', $request->sub_sub_product)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $subsubproduct = Factory_Sub_Sub_Product::where('id', $request->edit)->first();
            } else {
                $subsubproduct = new Factory_Sub_Sub_Product;
            }
            $subsubproduct->sub_product_id = $request->sub_product_id;
            $subsubproduct->sub_sub_product = $request->sub_sub_product;
            $subsubproduct->save();
        } else {
            return redirect('Master/subsubproduct')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/subsubproduct')->with('success', 'Added Successfully...');
    }

    public function delete_subsubproduct($id)
    {
        Factory_Sub_Sub_Product::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function uoms($id = null)
    {
        $uoms = Factory_Uom::all();
        $edit = Factory_Uom::find($id);

        return view('Master.Plant.UOMS', ['uoms' => $uoms, 'edit' => $edit]);
    }

    public function uoms_store(Request $request)
    {
        $duplicate = Factory_Uom::where('UOMs', $request->UOMs)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $uom = Factory_Uom::where('id', $request->edit)->first();
            } else {
                $uom = new Factory_Uom;
            }

            $uom->UOMs = $request->UOMs;
            $uom->save();
        } else {
            return redirect('Master/uoms')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/uoms')->with('success', 'Added Successfully...');
    }

    public function delete_uoms($id)
    {
        Factory_Uom::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Accessories($id = null)
    {
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $Accessories = Master_Accessories::all();
        $Accessories_arr = array();
        foreach ($Accessories as $val) {
            $val->machinecode = Master_Machine_Code::find($val->Machine_Code_id);
            if (!empty($val->machinecode)) {
                $val->MachineName = Master_Machine_Name::find($val->machinecode->Machine_Name_id);
            }

            array_push($Accessories_arr, $val);
        }
        $edit = Master_Accessories::find($id);
        $editcode = '';
        if (isset($edit->id)) {
            $editcode = Master_Machine_Code::where('id', $edit->Machine_Code_id)->first();
        }

        return view('Master.Plant.Accessories', ['Accessories' => $Accessories_arr, 'MachineCode' => $Machine_Code, 'Machine_Name' => $Machine_Name, 'edit' => $edit, 'editcode' => $editcode]);
    }

    public function Accessories_store(Request $request)
    {
        $duplicate = Master_Accessories::where('Accessories', $request->Accessories)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Accessories = Master_Accessories::where('id', $request->edit)->first();
            } else {
                $Accessories = new Master_Accessories;
            }

            $Accessories->Machine_Code_id = $request->Machine_Code_id;
            $Accessories->Accessories = $request->Accessories;
            $Accessories->save();
        } else {
            return redirect('Master/Accessories')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Accessories')->with('success', 'Successfully...');
    }

    public function delete_Accessories($id)
    {
        Master_Accessories::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Duration($id = null)
    {
        $Duration = Master_Duration::all();
        $edit = Master_Duration::find($id);

        return view('Master.Plant.Duration', ['Duration' => $Duration, 'edit' => $edit]);
    }

    public function Duration_store(Request $request)
    {
        $duplicate = Master_Duration::where('Duration', $request->Duration)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Duration = Master_Duration::where('id', $request->edit)->first();
            } else {
                $Duration = new Master_Duration;
            }

            $Duration->Duration = $request->Duration;
            $Duration->save();
        } else {
            return redirect('Master/Duration')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Duration')->with('success', 'Successfully...');
    }

    public function delete_Duration($id)
    {
        Master_Duration::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Machine_Code($id = null)
    {
        $Machine_Name = Master_Machine_Name::all();
        $Machine_Code = Master_Machine_Code::all();
        $MachineCode_arr = array();
        foreach ($Machine_Code as $val) {
            $val->MachineName = Master_Machine_Name::find($val->Machine_Name_id);
            array_push($MachineCode_arr, $val);
        }
        $edit = Master_Machine_Code::find($id);

        return view('Master.Plant.Machine_Code', ['Machine_Code' => $MachineCode_arr, 'Machine_Name' => $Machine_Name, 'edit' => $edit]);
    }

    public function Machine_Code_store(Request $request)
    {
        $duplicate = Master_Machine_Code::where('Machine_Code', $request->Machine_Code)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Machine_Code = Master_Machine_Code::where('id', $request->edit)->first();
            } else {
                $Machine_Code = new Master_Machine_Code;
            }

            $Machine_Code->Machine_Name_id = $request->Machine_Name_id;
            $Machine_Code->Machine_Code = $request->Machine_Code;
            $Machine_Code->save();
        } else {
            return redirect('Master/Machine_Code')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Machine_Code')->with('success', 'Successfully...');
    }

    public function delete_Machine_Code($id)
    {
        Master_Machine_Code::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Machine_Name($id = null)
    {
        $Machine_Name = Master_Machine_Name::all();
        $edit = Master_Machine_Name::find($id);

        return view('Master.Plant.Machine_Name', ['Machine_Name' => $Machine_Name, 'edit' => $edit]);
    }

    public function Machine_Name_store(Request $request)
    {
        $duplicate = Master_Machine_Name::where('Machine_Name', $request->Machine_Name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Machine_Name = Master_Machine_Name::where('id', $request->edit)->first();
            } else {
                $Machine_Name = new Master_Machine_Name;
            }

            $Machine_Name->Machine_Name = $request->Machine_Name;
            $Machine_Name->Supplier_Name = $request->Supplier_Name;
            $Machine_Name->Machine_Date = $request->Machine_Date;
            $Machine_Name->Machine_Purpose = $request->Machine_Purpose;
            $Machine_Name->save();
        } else {
            return redirect('Master/Machine_Name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Machine_Name')->with('success', 'Successfully...');
    }

    public function delete_Machine_Name($id)
    {
        Master_Machine_Name::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Make_Model($id = null)
    {
        $Make_Model = Master_Make_Model::all();
        $edit = Master_Make_Model::find($id);

        return view('Master.Plant.Make_Model', ['Make_Model' => $Make_Model, 'edit' => $edit]);
    }

    public function Make_Model_store(Request $request)
    {
        $duplicate = Master_Make_Model::where('Make_Model', $request->Make_Model)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Make_Model = Master_Make_Model::where('id', $request->edit)->first();
            } else {
                $Make_Model = new Master_Make_Model;
            }

            $Make_Model->Make_Model = $request->Make_Model;
            $Make_Model->save();
        } else {
            return redirect('Master/Make_Model')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Make_Model')->with('success', 'Successfully...');
    }

    public function delete_Make_Model($id)
    {
        Master_Make_Model::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Production_Capacity($id = null)
    {
        $Production_Capacity = Master_Production_Capacity::all();
        $edit = Master_Production_Capacity::find($id);

        return view('Master.Plant.Production_Capacity', ['Production_Capacity' => $Production_Capacity, 'edit' => $edit]);
    }

    public function Production_Capacity_store(Request $request)
    {
        $duplicate = Master_Production_Capacity::where('Production_Capacity', $request->Production_Capacity)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Production_Capacity = Master_Production_Capacity::where('id', $request->edit)->first();
            } else {
                $Production_Capacity = new Master_Production_Capacity;
            }

            $Production_Capacity->Production_Capacity = $request->Production_Capacity;
            $Production_Capacity->save();
        } else {
            return redirect('Master/Production_Capacity')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Production_Capacity')->with('success', 'Successfully...');
    }

    public function delete_Production_Capacity($id)
    {
        Master_Production_Capacity::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Specification($id = null)
    {
        $Specification = Master_Specification::all();
        $edit = Master_Specification::find($id);

        return view('Master.Plant.Specification', ['Specification' => $Specification, 'edit' => $edit]);
    }

    public function Specification_store(Request $request)
    {
        $duplicate = Master_Specification::where('Specification', $request->Specification)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Specification = Master_Specification::where('id', $request->edit)->first();
            } else {
                $Specification = new Master_Specification;
            }

            $Specification->Specification = $request->Specification;
            $Specification->save();
        } else {
            return redirect('Master/Specification')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Specification')->with('success', 'Successfully...');
    }

    public function delete_Specification($id)
    {
        Master_Specification::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Warranty($id = null)
    {
        $Warranty = Master_Warranty::all();
        $edit = Master_Warranty::find($id);

        return view('Master.Plant.Warranty', ['Warranty' => $Warranty, 'edit' => $edit]);
    }

    public function Warranty_store(Request $request)
    {
        $duplicate = Master_Warranty::where('Warranty', $request->Warranty)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Warranty = Master_Warranty::where('id', $request->edit)->first();
            } else {
                $Warranty = new Master_Warranty;
            }

            $Warranty->Warranty = $request->Warranty;
            $Warranty->save();
        } else {
            return redirect('Master/Warranty')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Warranty')->with('success', 'Successfully...');
    }

    public function delete_Warranty($id)
    {
        Master_Warranty::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Manufacturing_unit($id = null)
    {
        $Manufacturing_unit = Master_Manufacturing_unit::all();
        $edit = Master_Manufacturing_unit::find($id);

        return view('Master.Plant.Manufacturing_unit', ['Manufacturing_unit' => $Manufacturing_unit, 'edit' => $edit]);
    }

    public function Manufacturing_unit_store(Request $request)
    {
        $duplicate = Master_Manufacturing_unit::where('Manufacturing_unit', $request->Manufacturing_unit)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $Manufacturing_unit = Master_Manufacturing_unit::where('id', $request->edit)->first();
            } else {
                $Manufacturing_unit = new Master_Manufacturing_unit;
            }

            $Manufacturing_unit->Manufacturing_unit = $request->Manufacturing_unit;
            $Manufacturing_unit->save();
        } else {
            return redirect('Master/Manufacturing_unit')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Manufacturing_unit')->with('success', 'Successfully...');
    }

    public function delete_Manufacturing_unit($id)
    {
        Master_Manufacturing_unit::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function BU($id = null)
    {
        $BU = Master_BU::all();
        $edit = Master_BU::find($id);

        return view('Master.Plant.BU', ['BU' => $BU, 'edit' => $edit]);
    }

    public function BU_store(Request $request)
    {
        $duplicate = Master_BU::where('BU', $request->BU)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $BU = Master_BU::where('id', $request->edit)->first();
            } else {
                $BU = new Master_BU;
            }

            $BU->BU = $request->BU;
            $BU->save();
        } else {
            return redirect('Master/BU')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/BU')->with('success', 'Successfully...');
    }

    public function delete_BU($id)
    {
        Master_BU::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function quality_check($id = null)
    {
        $quality_check = Master_Quality_Check::all();
        $edit = Master_Quality_Check::find($id);

        return view('Master.Plant.quality_check', ['quality_check' => $quality_check, 'edit' => $edit]);
    }

    public function quality_check_store(Request $request)
    {
        $duplicate = Master_Quality_Check::where('quality_check', $request->quality_check)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $quality_check = Master_Quality_Check::where('id', $request->edit)->first();
            } else {
                $quality_check = new Master_Quality_Check;
            }

            $quality_check->quality_check = $request->quality_check;
            $quality_check->save();
        } else {
            return redirect('Master/quality_check')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/quality_check')->with('success', 'Successfully...');
    }

    public function delete_quality_check($id)
    {
        Master_Quality_Check::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function category($id = null)
    {
        $category = Master_category::all();
        $edit = Master_category::find($id);

        return view('Master.Plant.category', ['category' => $category, 'edit' => $edit]);
    }

    public function category_store(Request $request)
    {
        $duplicate = Master_category::where('category', $request->category)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $category = Master_category::where('id', $request->edit)->first();
            } else {
                $category = new Master_category;
            }

            $category->category = $request->category;
            $category->save();
        } else {
            return redirect('Master/category')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/category')->with('success', 'Successfully...');
    }

    public function delete_category($id)
    {
        Master_category::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Customer_Name($id = null)
    {
        $Customer_Name = Master_Customer_Name::all();
        $edit = Master_Customer_Name::find($id);

        return view('Master.Plant.Customer_Name', ['Customer_Name' => $Customer_Name, 'edit' => $edit]);
    }

    public function Customer_Name_store(Request $request)
    {
        $duplicate = Master_Customer_Name::where('Customer_Name', $request->Customer_Name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $CustomerName = Master_Customer_Name::where('id', $request->edit)->first();
            } else {
                $CustomerName = new Master_Customer_Name;
                $CustomerName->userID = auth()->user()->id;
            }

            $CustomerName->Customer_Name = $request->Customer_Name;
            $CustomerName->save();
        } else {
            return redirect('Master/Customer_Name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Customer_Name')->with('success', 'Successfully...');
    }

    public function delete_Customer_Name($id)
    {
        Master_Customer_Name::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Company_Name($id = null)
    {
        $Company_Name = Master_Company_Name::all();
        $edit = Master_Company_Name::find($id);

        return view('Master.Plant.Company_Name', ['Company_Name' => $Company_Name, 'edit' => $edit]);
    }

    public function Company_Name_store(Request $request)
    {
        $duplicate = Master_Company_Name::where('Company_Name', $request->Company_Name)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $CompanyName = Master_Company_Name::find($request->edit);
            } else {
                $CompanyName = new Master_Company_Name;
                $CompanyName->userID = auth()->user()->id;
            }
            $CompanyName->Company_Name = $request->Company_Name;
            $CompanyName->save();
        } else {
            return redirect('Master/Company_Name')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Company_Name')->with('success', 'Successfully...');
    }

    public function delete_Company_Name($id)
    {
        Master_Company_Name::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function Work_Order_Status($id = null)
    {
        $Work_Order_Status = Master_Work_Order_Status::all();
        $edit = Master_Work_Order_Status::find($id);

        return view('Master.Plant.Work_Order_Status', ['Work_Order_Status' => $Work_Order_Status, 'edit' => $edit]);
    }

    public function Work_Order_Status_store(Request $request)
    {
        $duplicate = Master_Work_Order_Status::where('Work_Order_Status', $request->Work_Order_Status)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $WorkOrderStatus = Master_Work_Order_Status::find($request->edit);
            } else {
                $WorkOrderStatus = new Master_Work_Order_Status;
                $WorkOrderStatus->userID = auth()->user()->id;
            }
            $WorkOrderStatus->Work_Order_Status = $request->Work_Order_Status;
            $WorkOrderStatus->save();
        } else {
            return redirect('Master/Work_Order_Status')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/Work_Order_Status')->with('success', 'Successfully...');
    }

    public function delete_Work_Order_Status($id)
    {
        Master_Work_Order_Status::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
