<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Create_pdf;
use App\Models\Market_segment;
use App\Models\Competition_index;
use App\Models\Segment_subtype;
use App\Models\Segment_type;
use App\Models\Client;

use Illuminate\Support\Facades\Auth;

use Hash;

use PDF;
use App;
use SoapClient;

class PdfCreateController extends Controller
{

    public function index()
    {
        $res = Create_pdf::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        
        $res_arr = array();
        foreach ($res as $val) {
            $val->client = Client::find($val->client_id);
            array_push($res_arr,$val);
        }
       
        return view('research.pdfdata', ['data' => $res]);
    }
    public function create_pdf_get()
    {
        $client = Client::all();
        return view('research.createpdf', ['client' => $client]);
    }
    public function create_pdf(Request $request)
    {
        $res = new Create_pdf;
        $res->company_type = $request->company_type;
        $res->company_name = isset($request->company_name) && $request->company_name != '' ? $request->company_name : '';
        $res->start_year = $request->start_year;
        $res->end_year = $request->end_year;
        $res->client_id = $request->client_id;
        $res->user_id = Auth::guard('admin')->id();;
        $response = $res->save();
        $inputs = $request->input();
        if (isset($inputs['mr_seg']) && !empty($inputs['mr_seg'])) {
            foreach ($inputs['mr_seg'] as $key => $mr_seg) {
                if (!empty($mr_seg)) {
                    $marke = new Market_segment;
                    $marke->create_pdf_id = $res->id;
                    $marke->market_segment = $mr_seg;
                    $marke->save();
                    if (isset($inputs['seg_type'][$key]) && !empty($inputs['seg_type'][$key])) {
                        foreach ($inputs['seg_type'][$key] as $key_j => $seg_typ) {
                            if (!empty($seg_typ)) {
                                $markes = new Segment_type;
                                $markes->market_segment_id = $marke->id;
                                $markes->segment_type = $seg_typ;
                                $markes->save();
                                if (isset($inputs['seg_sub_type'][$key][$key_j]) && !empty($inputs['seg_sub_type'][$key][$key_j])) {
                                    foreach ($inputs['seg_sub_type'][$key][$key_j] as $seg_sub_typ) {
                                        if (!empty($seg_sub_typ)) {
                                            $markess = new Segment_subtype;
                                            $markess->segment_type_id = $markes->id;
                                            $markess->segment_subtype = $seg_sub_typ;
                                            $markess->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (isset($inputs['company']) && !empty($inputs['company'])) {
            foreach ($inputs['company'] as $key => $value) {
                $comp = new Competition_index;
                $comp->create_pdf_id = $res->id;
                $comp->company = $value;
                $comp->save();
            }
        }


        if ($response) {
            $client_pdf_status = Client::where('id', $request->client_id)->update(['pdf_status' => 1]);
            return redirect("/research/pdf_list/")->withSuccess("Added Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }
    ///////////////////
    public function loadpdf($id)
    {
        //$id = 3;
        $data = Create_pdf::find($id);
        $market_segment = Market_segment::where('create_pdf_id', $id)->get();
        $compettiton = Competition_index::where('create_pdf_id', $id)->get();
        $array = array();
        foreach ($market_segment as $market_data) {
            $segment_type = array();
            $markes_type = Segment_type::where('market_segment_id', $market_data->id)->get();
            foreach ($markes_type as $market_type_data) {

                $market_type_data->segment_subtype = Segment_subtype::where('segment_type_id', $market_type_data->id)->get();
                array_push($segment_type, $market_type_data);
            }
            $market_data->segment_type = $segment_type;
            array_push($array, $market_data);
        }
        $data->Competition_index = $compettiton;
        $data->segment = $array;


        return ['data' => $data];
    }
    public function loadpdfdata($id)
    {
        return view('newpdf', $this->loadpdf($id));
    }
    function downloadpdf($id)
    {

        //    return view('newpdf', $this->loadpdf($id));
        //    die;
        $pdf = App::make('dompdf.wrapper');
        //$pdf->loadHTML($html);
        $pdf->loadView('newpdf', $this->loadpdf($id));
        $pdf->setPaper('a4', 'landscape');
        $pdf->stream();
        $finnam = strtotime('now') . '.pdf';
        $path = storage_path('app/pdf/');
        return $pdf->download($finnam);
        //return url('storage/app/pdf') .'/'. $finnam;
        //return 1;

    }
}
