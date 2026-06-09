<?php

namespace App\Http\Controllers\ProductCategories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategories\{ProductCategories_Add_Product, ProductCategories_Add_Product_Other, ProductCategories_Approve};
use App\Models\MaterialManagement\{MaterialManagement_Add_Material};
use Session;

class ProductCategoriesController extends Controller
{
    public function AddProduct(Request $request)
    {
        //return $request->all();
        if ($request->edit) {
            $addproduct = ProductCategories_Add_Product::find($request->edit);
            $addproduct->remarks = $request->remarks;
            $addproduct->save();
        } else {
            $addproduct = new ProductCategories_Add_Product;
            $addproduct->userID = auth()->user()->id;
            $addproduct->Forward_Status = 0;

            $addproduct->Organization_Name = $request->Organization_Name;
            $addproduct->Manufacturing_Unit = $request->Manufacturing_Unit;
            $addproduct->BU = $request->BU;
            $addproduct->Plant_Name = $request->Plant_Name;
            $addproduct->Product = $request->Product;
            $addproduct->Sub_Product = $request->Sub_Product;
            $addproduct->Sub_Sub_Product = $request->Sub_Sub_Product;
            $addproduct->Company_Name = $request->Company_Name;
            $addproduct->Colour = $request->Colour;
            $addproduct->Size = $request->Size;
            $addproduct->Category = $request->Category;
            $addproduct->Lable = $request->Lable;
            $addproduct->Raw_Material = $request->Raw_Material;
            $addproduct->HSN_Code = $request->HSN_Code;
            $addproduct->UOM = $request->UOM;
            $addproduct->remarks = $request->remarks;
            if (!isset($request->draft)) {
                $addproduct->status = 0;
            } else {
                $addproduct->status = 1;
            }

             $material_apprvsts= productcategories_add_product::select('productcategories_add_product.Approve_status')->where('Raw_Material',$request->Raw_Material)->orderBy('id', 'DESC')->first();
                if (isset($material_apprvsts)) {
                    if($material_apprvsts->Approve_status=="" || $material_apprvsts->Approve_status=="APPROVE" || $material_apprvsts->Approve_status=="RECHECK"|| $material_apprvsts->Approve_status=="HOLD" || $material_apprvsts->Approve_status=="FORWARD" ||$material_apprvsts->Approve_status=="OBJECT"){
                        return redirect('ProductCategories/ProductList')->withErrors( 'Already Added...');
                    }
                    else {
                        $addproduct->save();
                    }
                } 
            else {
                $addproduct->save();
            }
            
        }
        

        if ($request->edit != '') {
            $Product_ID = $request->edit;
        } else {
            $Product_ID =  $addproduct->id;
        }

        $res = $request->input();
        if (isset($res['manual_field']) && $res['manual_field'] != '' || isset($res['productID']) && $res['productID'] != '') {
            foreach ($res['manual_field'] as $key => $val) {
                $productID = isset($res['productID'][$key]) ? $res['productID'][$key] : '';
                if ($productID != '') {
                    $productother = ProductCategories_Add_Product_Other::where('id', $productID)->update(['manual_field' => $res['manual_field'][$key] ?? '']);
                } else {
                    $productother = new ProductCategories_Add_Product_Other;
                    $productother->Add_Product_ID = $Product_ID;
                    $productother->manual_field = $res['manual_field'][$key] ?? '';

                    $productother->save();
                }
            }
        }

        if (!isset($request->draft)) {
            $UsedStatus = MaterialManagement_Add_Material::where('id', $request->Raw_Material)->update(['Used_Status' => 1]);
        }

        if (!isset($request->draft)) {
            $Approve_step = ProductCategories_Add_Product::where('id', $Product_ID)->update(['Approve_Step' => 1]);
        }

        if ($request->edit != '' && !isset($request->draft)) {
            $dataStatus = ProductCategories_Add_Product::find($request->edit);
            if ($dataStatus->Approve_status != '') {
                $rechecked = ProductCategories_Add_Product::where('id', $request->edit)->update(['Approve_status' => null]);
                $status = ProductCategories_Approve::where('Product_id', $request->edit)->where('status', 1)->update(['status' => 0]);

                $approve = new ProductCategories_Approve;
                $approve->userID = auth()->user()->id;
                if (auth()->user()->role == 0) {
                    $approve->role = 'Admin';
                } else {
                    $approve->role = 'Inputer';
                }
                $approve->Product_id = $request->edit;
                $approve->status = 1;
                $approve->action = 'Checked';
                $approve->ip_address = $request->getClientIp();
                $approve->device_name = $request->header('User-Agent');

                $approve->save();
            }
        }

        return redirect('ProductCategories/ProductList')->with('success', 'Added Successfully....');
    }
}
