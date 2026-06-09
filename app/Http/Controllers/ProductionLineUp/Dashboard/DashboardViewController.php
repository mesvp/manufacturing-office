<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;

class DashboardViewController extends Controller
{
    public function dashboard()
    {
        return view('Dashboard.dashboard');
    }
}
