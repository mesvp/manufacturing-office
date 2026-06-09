<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Pdf_feedback;



class ResearchController extends Controller
{

    public function dashboard()
    {
        $user = Admin::find(auth()->user()->id);
        $total_clients = Client::count();
        $assigned = Client::where('assign_to_research',$user->name)->count();
        
        return view('research.dashboard',['total_clients'=>$total_clients,'assigned'=>$assigned]);
    }

    public function index()
    {
        $user = Admin::find(auth()->user()->id);
        $client = Client::where('assign_to_research', $user->name)->get();

        return view('research.employee.client', ['client' => $client]);
    }


    public function pdf_page($id)
    {
        $client  = Client::find($id);

        return view('research.employee.pdf_feedback', ['client' => $client]);
    }

    public function pdf_feedback(Request $req)
    {
        $validated = $req->validate([     
            'Description' => 'required'                       
        ]);

        $client = Client::find($req->id);

        $feedback = new Pdf_feedback;
        $feedback->researcher_id = auth()->user()->id;
        $feedback->client_id = $req->id;
        $feedback->salesmen_id = $client->sales_id;
        $feedback->feedback_status = 1;
        $feedback->Description = $req->Description;

        $feedback->save();

        if ($feedback) {
            return redirect('research/client')->with('PDF Created Successfully');
        } else {
            return back()->with('Something Went Wrong');
        }
    }


    public function feedback()
    {
        $feedback = Pdf_feedback::where('researcher_id', auth()->user()->id)->where('feedback_status', '0')->get();

        $client_arr = array();
        foreach ($feedback as $val) {
            $val->client = Client::find($val->client_id);
            array_push($client_arr, $val);
        }


        return view('research.employee.client_feedback', ['feedback' => $client_arr]);
    }



    public function show_return($id)
    {
        $clientss  = Client::find($id);

        return view('research.employee.return_feedback', ['client' => $clientss]);
    }


    public function return_feedback(Request $req)
    {
        $validated = $req->validate([     
            'Description' => 'required'                       
        ]);
        
        $client = Client::find($req->id);

        $feedback = new Pdf_feedback;
        $feedback->researcher_id = auth()->user()->id;
        $feedback->client_id = $req->id;
        $feedback->salesmen_id = $client->sales_id;
        $feedback->feedback_status = 1;
        $feedback->Description = $req->Description;

        $feedback->save();

        if ($feedback) {
            return redirect('research/return-feedback')->with('Feedback submitted Successfully');
        } else {
            return back()->with('Something Went Wrong');
        }
    }
}
