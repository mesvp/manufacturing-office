<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Client;



class TlResearchController extends Controller
{
    public function dashboard()
    {
        $user = Admin::find(auth()->user()->id);
        $total_clients = Client::count();
        $assigned = Client::where('assign_to_researchTL',$user->name)->count();
        
        return view('tlresearch.dashboard',['total_clients'=>$total_clients,'assigned'=>$assigned]);
    }

    public function index()
    {
        $user = Admin::find(auth()->user()->id);
        $data = Client::where('feedback', '1')->orderBy('assign_to_research', 'ASC')->get();

        $assign = Admin::where('role','5')->where('status','0')->get();

        return view('tlresearch.pages.index', ['user'=>$user,'data' => $data,'assign'=>$assign]);
    }

    public function assign($id)
    {
        $user = Admin::find(auth()->user()->id);
        $assign = Admin::where('role','5')->where('status','0')->get();
        $edit = Client::find($id);

        return view('tlresearch.pages.assign', ['user'=>$user,'assign'=>$assign,'edit'=>$edit]);
    }

    public function assign_check(Request $req)
    {
        $validated = $req->validate([           
            'assign_to_research' => 'required',                     
        ]);
        $value = explode(',', $req->id);
        foreach ($value as $val) {
            $check = Client::find($val);
            $check->research_TL_ID = auth()->user()->id;
            $check->assign_to_research = $req->assign_to_research;

            $check->save();
        }

        if ($check) {
            return redirect('tlresearch/client')->withSuccess("Assigned Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }
}
