<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Client;
use Hash;


class TlSalesController extends Controller
{
    public function dashboard()
    {
        $user = Admin::find(auth()->user()->id);
        $total_clients = Client::count();
        $assigned = Client::where('assign_to_salesTl',$user->name)->count();
        
        return view('tlsales.dashboard',['total_clients'=>$total_clients,'assigned'=>$assigned]);
    }
    
    public function index()
    {
        $user = Admin::find(auth()->user()->id);
       
        $data = Client::where('assign_to_salesTl',$user->name)->orderBy('Assign_to', 'ASC')->get();
        $assign1 = Admin::where('role', '4')->where('status', '0')->get();

        return view('tlsales.Tlsales.Tlsales', ['user'=>$user,'data' => $data, 'assign1' => $assign1]);
    }

    public function edit_client1($id)
    {
        $user = Admin::find(auth()->user()->id);
        $edit = Client::find($id);
        $assign = Admin::where('role', '4')->where('status', '0')->get();

        return view('tlsales.Tlsales.edit_client', ['user'=>$user,'edit' => $edit, 'assign' => $assign]);
    }

    public function edit_client(Request $res)
    {
        $validated = $res->validate([           
            'Assign_to' => 'required',                     
        ]);

        $client = Client::find($res->id);
        $client->sales_TL_ID = auth()->user()->id;
        $client->Assign_to = $res->Assign_to;

        $client->save();

        if ($client) {
            return redirect('tlsales/tlsales')->withSuccess("Added Successfully...");
        } else {
            return back()->withError("Something went wrong!");
        }
    }

    public function assign(Request $req)
    {
        $validated = $req->validate([           
            'Assign_to' => 'required',                     
        ]);
        $value = explode(',', $req->id);
        foreach ($value as $val) {
            $check = Client::find($val);
            $check->sales_TL_ID = auth()->user()->id;
            $check->Assign_to = $req->Assign_to;

            $check->save();
        }

        if ($check) {
            return redirect('tlsales/tlsales')->withSuccess("Added Successfully...");
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
}
