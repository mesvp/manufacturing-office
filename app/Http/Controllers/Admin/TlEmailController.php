<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;

class TlEmailController extends Controller
{

    public function dashboard()
    {
        $total_clients = Client::count();
        
        return view('tlemail.dashboard',['total_clients'=>$total_clients]);
    }
    
}
