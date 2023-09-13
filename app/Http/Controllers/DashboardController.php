<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grievance;
use App\Models\Progress;
use App\Models\Contact;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_grievances = Grievance::count();
        $total_contacts = Contact::count();
        $total_progress = Progress::count();
        return view('admin.dashboard.index',compact('total_progress','total_grievances','total_contacts'));
    }

}
