<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Client;
use App\Models\Client_active;
use App\Models\Client_pipline;
use App\Models\Client_warm;
use App\Models\Client_closed;
use Hash;
use stdClass;


class AdminController extends Controller
{

    public function index()
    {
        $data = Client::all();

        $data_arr = array();
        foreach ($data as $val) {
            $val->active = Client_active::where('clientID', $val->id)->where('status', '0')->first();
            $val->pipline = Client_pipline::where('clientID', $val->id)->where('status', '0')->first();
            $val->warm = Client_warm::where('clientID', $val->id)->where('status', '0')->first();
            $val->closed = Client_closed::where('clientID', $val->id)->where('status', '0')->first();

            array_push($data_arr, $val);
        }

        return view('admin.pages.index', ['data' => $data]);
    }

    public function client()
    {
        $assign = Admin::where('role', '2')->get();

        return view('admin.pages.add_client', ['assign' => $assign]);
    }

    public function edit_client($id)
    {
        $edit = Client::where('id', $id)->first();

        $edit->active = Client_active::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->pipline = Client_pipline::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->warm = Client_warm::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();
        $edit->closed = Client_closed::where('userID', auth()->user()->id)->where('clientID', $id)->where('status', '0')->first();

        $assign = Admin::where('role', '2')->get();

        return view('admin.pages.edit_client', ['edit' => $edit, 'assign' => $assign]);
    }

    public function add_client(Request $res)
    {

        $rules = [
            'assign_to_salesTl' => 'required',
            'client_name' => 'required',
            'client_email' => 'required|email',
            'report_title' => 'required',
            'client_company' => 'required',
            'client_region' => 'required',
            'client_linkedin' => 'required',
            'description' => 'required',
            'client_requirements' => 'required',
            'raise_to_research' => 'required',
            'sales_comments' => 'required',
            'client_status' => 'required',
        ];

        if ($res->id) {
            $rules['client_email'] .= "";
        } else {
            $rules['client_email'] .= '|unique:clients';
        }

        $validated = $res->validate($rules);

        if ($res->id) {
            $client = Client::find($res->id);
        } else {
            $client = new Client;
            $client->email_empID = auth()->user()->id;
        }
        $client->assign_to_salesTl = $res->assign_to_salesTl;
        $client->feedback = 0;
        $client->client_name = $res->client_name;
        $client->client_email = $res->client_email;

        if ($res->client_mobile != '') {
            $client->client_mobile = $res->client_mobile;
        }

        $client->report_title = $res->report_title;
        $client->client_company = $res->client_company;
        $client->client_region = $res->client_region;

        if ($res->client_country != '') {
            $client->client_country = $res->client_country;
        }

        $client->client_linkedin = $res->client_linkedin;
        $client->description = $res->description;


        $client->sales_id = auth()->user()->id;
        $client->client_requirements = $res->client_requirements;
        $client->raise_to_research = $res->raise_to_research;
        $client->sales_comments = $res->sales_comments;
        $client->client_status = $res->client_status;
        $client->save();

        if ($res->client_status == 'Active') {

            Client_active::where('clientID', $client->id)->update(['status' => 1]);

            $client_active = new Client_active;
            $client_active->userID = auth()->user()->id;
            $client_active->clientID = $client->id;
            $client_active->followup_date = $res->followup_date;
            $client_active->status = 0;
            $client_active->save();
        } else {
            Client_active::where('clientID', $client->id)->update(['status' => 1]);
        }

        if ($res->client_status == 'Pipline') {
            Client_pipline::where('clientID', $client->id)->update(['status' => 1]);

            $client_pipline = new Client_pipline;
            $client_pipline->userID = auth()->user()->id;
            $client_pipline->clientID = $client->id;
            $client_pipline->pipline_reason = $res->pipline_reason;
            $client_pipline->followup_date = $res->followup_date1;
            $client_pipline->status = 0;
            $client_pipline->save();
        } else {
            Client_pipline::where('clientID', $client->id)->update(['status' => 1]);
        }
        if ($res->client_status == 'Warm') {
            Client_warm::where('clientID', $client->id)->update(['status' => 1]);

            $client_warm = new Client_warm;
            $client_warm->userID = auth()->user()->id;
            $client_warm->clientID = $client->id;
            $client_warm->followup_date = $res->followup_date2;
            $client_warm->status = 0;
            $client_warm->save();
        } else {
            Client_warm::where('clientID', $client->id)->update(['status' => 1]);
        }

        if ($res->client_status == 'Closed') {

            Client_closed::where('clientID', $client->id)->update(['status' => 1, 'dispatch_date_status' => 1]);

            $client_closed = new Client_closed;
            $client_closed->userID = auth()->user()->id;
            $client_closed->clientID = $client->id;
            $client_closed->ticket_size = $res->ticket_size;
            $client_closed->mode_of_payment = $res->mode_of_payment;
            $client_closed->dispatch = $res->dispatch;
            if ($res->dispatch == 'Dispatch Date') {
                $client_closed->dispatch_date = $res->dispatch_date;
                $client_closed->dispatch_date_status = 0;
            }
            $client_closed->status = 0;
            $client_closed->save();
        } else {
            Client_closed::where('clientID', $client->id)->update(['status' => 1, 'dispatch_date_status' => 1]);
        }

        if ($res->client_status == 'Lost') {
            $client->lost_reason = $res->lost_reason;
        }else{
            Client::find($client->id)->update(['lost_reason' => '']);
        }

        $client->save();
        Client::where('id', $client->id)->update(['uniqueID' => substr(strtoupper($client->client_email), 0, 2) . $client->id]);

        if ($client) {
            return redirect('admin/client')->withSuccess("Added Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }

    public function destroy($id)
    {
        $res = Client::findOrfail(decrypt($id));
        if ($res->delete()) {
            return back()->withSuccess("Deleted Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }

    public function previous_details($id)
    {
        $details = Client::where('id', $id)->get();

        $details_arr = array();
        foreach ($details as $val) {
            $val->active = Client_active::where('clientID', $val->id)->get();
            $val->pipline = Client_pipline::where('clientID', $val->id)->get();
            $val->warm = Client_warm::where('clientID', $val->id)->get();
            $val->closed = Client_closed::where('clientID', $val->id)->get();

            array_push($details_arr, $val);
        }       

        return view('admin.pages.PreviousRecord', ['details' => $details]);
    }
}
