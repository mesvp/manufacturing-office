<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Client_active;
use App\Models\Client_pipline;
use App\Models\Client_warm;
use App\Models\Client_closed;
use App\Models\Pdf_feedback;
use App\Models\Create_pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientExport;

class SalesController extends Controller
{

    public function dashboard()
    {
        $user = Admin::find(auth()->user()->id);
        $total_clients = Client::count();
        $assigned = Client::where('Assign_to',$user->name)->count();
        
        return view('sales.dashboard',['total_clients'=>$total_clients,'assigned'=>$assigned]);
    }
    
    public function index()
    {
        $user = Admin::find(auth()->user()->id);
        $client = Client::where('Assign_to', $user->name)->where('pdf_status',0)->orderBy('feedback', 'ASC')->get();

        $client_arr = array();
        foreach ($client as $val) {
            $val->active = Client_active::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->pipline = Client_pipline::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->warm = Client_warm::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->closed = Client_closed::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();

            array_push($client_arr, $val);
        }

        return view('sales.sales.index', ['client' => $client]);
    }

    public function querylist()
    {
        $querylist = Client::where('sales_id',auth()->user()->id)->where('pdf_status',1)->orderBy('updated_at','ASC')->get();

        $querylist_arr = array();
        foreach ($querylist as $val) {
            $val->active = Client_active::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->pipline = Client_pipline::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->warm = Client_warm::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->closed = Client_closed::where('userID', auth()->user()->id)->where('clientID', $val->id)->where('status', '0')->first();
            $val->create_pdf = Create_pdf::where('client_id',$val->id)->orderBy('created_at', 'DESC')->first();

            array_push($querylist_arr, $val);
        }

        return view('sales.sales.query_list',['querylist'=>$querylist]);
    }

    public function client_edit($id)
    {
        $edit = Client::where('id', $id)->first();

        $edit->active = Client_active::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->pipline = Client_pipline::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->warm = Client_warm::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->closed = Client_closed::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();

     
        return view('sales.sales.edit_client', ['edit' => $edit]);
    }

    public function update(Request $req)
    {
        $validated = $req->validate([
            'client_requirements' => 'required',
            'raise_to_research' => 'required',
            'sales_comments' => 'required',
            'client_status' => 'required',
        ]);

        $update = Client::find($req->id);
        $update->sales_id = auth()->user()->id;
        $update->client_requirements = $req->client_requirements;
        $update->raise_to_research = $req->raise_to_research;
        $update->sales_comments = $req->sales_comments;
        $update->client_status = $req->client_status;
        $update->save();

        if ($req->client_status == 'Active') {

            Client_active::where('clientID', $update->id)->update(['status' => 1]);

            $client_active = new Client_active;
            $client_active->userID = auth()->user()->id;
            $client_active->clientID = $update->id;
            $client_active->followup_date = $req->followup_date;
            $client_active->status = 0;
            $client_active->save();
        } else {
            Client_active::where('clientID', $update->id)->update(['status' => 1]);
        }

        if ($req->client_status == 'Pipline') {
            Client_pipline::where('clientID', $update->id)->update(['status' => 1]);

            $client_pipline = new Client_pipline;
            $client_pipline->userID = auth()->user()->id;
            $client_pipline->clientID = $update->id;
            $client_pipline->pipline_reason = $req->pipline_reason;
            $client_pipline->followup_date = $req->followup_date1;
            $client_pipline->status = 0;
            $client_pipline->save();
        } else {
            Client_pipline::where('clientID', $update->id)->update(['status' => 1]);
        }
        if ($req->client_status == 'Warm') {
            Client_warm::where('clientID', $update->id)->update(['status' => 1]);

            $client_warm = new Client_warm;
            $client_warm->userID = auth()->user()->id;
            $client_warm->clientID = $update->id;
            $client_warm->followup_date = $req->followup_date2;
            $client_warm->status = 0;
            $client_warm->save();
        } else {
            Client_warm::where('clientID', $update->id)->update(['status' => 1]);
        }

        if ($req->client_status == 'Closed') {

            Client_closed::where('clientID', $update->id)->update(['status' => 1, 'dispatch_date_status' => 1]);

            $client_closed = new Client_closed;
            $client_closed->userID = auth()->user()->id;
            $client_closed->clientID = $update->id;
            $client_closed->ticket_size = $req->ticket_size;
            $client_closed->mode_of_payment = $req->mode_of_payment;
            $client_closed->dispatch = $req->dispatch;
            if ($req->dispatch == 'Dispatch Date') {
                $client_closed->dispatch_date = $req->dispatch_date;
                $client_closed->dispatch_date_status = 0;
            }
            $client_closed->status = 0;
            $client_closed->save();
        } else {
            Client_closed::where('clientID', $update->id)->update(['status' => 1, 'dispatch_date_status' => 1]);
        }

        if ($req->client_status == 'Lost') {
            $update->lost_reason = $req->lost_reason;
        }

        $update->save();

        if ($update) {
            return redirect('sales/fresh-sample-leads')->withSuccess("updated Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }


    public function get_data($id)
    {
        $assign = Admin::where('role', '3')->get();
        $edit = Client::find($id);

        return view('sales.sales.assign', ['assign' => $assign, 'edit' => $edit]);
    }


    public function assignto(Request $req)
    {
        $validated = $req->validate([
            'assign_to_researchTL' => 'required',
        ]);

        $assignn = Client::find($req->id);
        $assignn->assign_to_researchTL = $req->assign_to_researchTL;
        $assignn->feedback = 1;

        $assignn->save();

        if ($assignn) {
            return redirect('sales/fresh-sample-leads')->withSuccess("updated Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }

    // public function feedback()
    // {
    //     $feedback = Pdf_feedback::where('salesmen_id', auth()->user()->id)->where('feedback_status', '1')->get();

    //     $client_arr = array();
    //     foreach ($feedback as $val) {
    //         $val->client = Client::find($val->client_id);
    //         array_push($client_arr, $val);
    //     }


    //     return view('sales.sales.client_feedback', ['feedback' => $client_arr]);
    // }

    // public function return_feed($id)
    // {
    //     $client  = Client::find($id);

    //     return view('sales.sales.return_feedback', ['client' => $client]);
    // }

    // public function return_feedback(Request $req)
    // {
    //     $validated = $req->validate([
    //         'Description' => 'required'
    //     ]);

    //     $client = Pdf_feedback::find($req->id);

    //     $return = new Pdf_feedback;
    //     $return->salesmen_id = auth()->user()->id;
    //     $return->researcher_id = $client->researcher_id;
    //     $return->client_id = $req->id;
    //     $return->feedback_status = 0;
    //     $return->Description = $req->Description;

    //     $return->save();

    //     if ($return) {
    //         return redirect('sales/client-feedback')->with('Return Feedback Submitted');
    //     } else {
    //         return back()->with('Something Went Wrong');
    //     }
    // }



    // public function export(Request $request)
    // {
    //     $user = Admin::find(auth()->user()->id);

    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');

    //     $client = Client::where('Assign_to', $user->name)
    //         ->where('feedback', '0')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->get();

    //     return Excel::download(new ClientExport($client), 'clients.xlsx');
    // }
}
