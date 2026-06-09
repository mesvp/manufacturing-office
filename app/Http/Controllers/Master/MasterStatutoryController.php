<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\{Master_Statutory_Factory_License_No, Master_Statutory_GST, Master_Statutory_Labour_License_No, Master_Statutory_PAN, Master_Statutory_Polution_Certificate_No};


class MasterStatutoryController extends Controller
{
    public function factory_license($id = null)
    {
        $factorylicense = Master_Statutory_Factory_License_No::all();
        $edit = Master_Statutory_Factory_License_No::find($id);

        return view('Master.Statutory.Factory_license', ['factorylicense' => $factorylicense, 'edit' => $edit]);
    }

    public function factory_license_store(Request $request)
    {
        $duplicate = Master_Statutory_Factory_License_No::where('factory_license_no', $request->factory_license_no)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $factoryLicense = Master_Statutory_Factory_License_No::find($request->edit);
            } else {
                $factoryLicense = new Master_Statutory_Factory_License_No;
            }
            $factoryLicense->factory_license_no = $request->factory_license_no;
            $factoryLicense->save();
        } else {
            return redirect('Master/factory_license')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/factory_license')->with('success', 'Added Successfully.....');
    }

    public function delete_factory_license($id)
    {
        Master_Statutory_Factory_License_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function GST($id = null)
    {
        $gst = Master_Statutory_GST::all();
        $edit = Master_Statutory_GST::find($id);

        return view('Master.Statutory.GST', ['gst' => $gst, 'edit' => $edit]);
    }

    public function GST_store(Request $request)
    {
        $duplicate = Master_Statutory_GST::where('GST_In_no', $request->GST_In_no)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $gst = Master_Statutory_GST::find($request->edit);
            } else {
                $gst = new Master_Statutory_GST;
            }
            $gst->GST_In_no = $request->GST_In_no;
            $gst->save();
        } else {
            return redirect('Master/GST')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/GST')->with('success', 'Added Successfully.....');
    }

    public function delete_GST($id)
    {
        Master_Statutory_GST::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function labour_license($id = null)
    {
        $labourLicense = Master_Statutory_Labour_License_No::all();
        $edit = Master_Statutory_Labour_License_No::find($id);

        return view('Master.Statutory.Labour_license', ['labourLicense' => $labourLicense, 'edit' => $edit]);
    }

    public function labour_license_store(Request $request)
    {
        $duplicate = Master_Statutory_Labour_License_No::where('labour_license_no', $request->labour_license_no)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $labourLicense = Master_Statutory_Labour_License_No::find($request->edit);
            } else {
                $labourLicense = new Master_Statutory_Labour_License_No;
            }
            $labourLicense->labour_license_no = $request->labour_license_no;
            $labourLicense->save();
        } else {
            return redirect('Master/labour_license')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/labour_license')->with('success', 'Added Successfully.....');
    }

    public function delete_labour_license($id)
    {
        Master_Statutory_Labour_License_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function pan($id = null)
    {
        $pan = Master_Statutory_PAN::all();
        $edit = Master_Statutory_PAN::find($id);

        return view('Master.Statutory.PAN', ['pan' => $pan, 'edit' => $edit]);
    }

    public function pan_store(Request $request)
    {
        $duplicate = Master_Statutory_PAN::where('pan_no', $request->pan_no)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $pan = Master_Statutory_PAN::find($request->edit);
            } else {
                $pan = new Master_Statutory_PAN;
            }
            $pan->pan_no = $request->pan_no;
            $pan->save();
        } else {
            return redirect('Master/pan')->with('error', 'can not save duplicate data....');
        }
        return redirect('Master/pan')->with('success', 'Added Successfully.....');
    }

    public function delete_pan($id)
    {
        Master_Statutory_PAN::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }

    public function polution_certificate($id = null)
    {
        $polutionCertificate = Master_Statutory_Polution_Certificate_No::all();
        $edit = Master_Statutory_Polution_Certificate_No::find($id);

        return view('Master.Statutory.Polution_Certificate', ['polutionCertificate' => $polutionCertificate, 'edit' => $edit]);
    }

    public function polution_certificate_store(Request $request)
    {
        $duplicate = Master_Statutory_Polution_Certificate_No::where('polution_certificate', $request->polution_certificate)->count();
        if ($duplicate == 0) {
            if ($request->edit != '') {
                $polutionCertificate = Master_Statutory_Polution_Certificate_No::find($request->edit);
            } else {
                $polutionCertificate = new Master_Statutory_Polution_Certificate_No;
            }
            $polutionCertificate->polution_certificate = $request->polution_certificate;
            $polutionCertificate->save();
        } else {
            return redirect('Master/polution_certificate')->with('errorss', 'can not save duplicate data....');
        }
        return redirect('Master/polution_certificate')->with('success', 'Added Successfully.....');
    }

    public function delete_polution_certificate($id)
    {
        Master_Statutory_Polution_Certificate_No::find($id)->delete();

        return back()->with('success', 'Deleted Successfully...');
    }
}
