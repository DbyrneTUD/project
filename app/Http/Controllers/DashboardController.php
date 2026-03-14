<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $myGroups = auth()->user()->groups()->latest()->paginate(3);

        $myRequests = auth()->user()->liftRequests()->where('status', '!=', 'completed')->latest()->get();

        $myDrivingTrips = auth()->user()->drivingTrips()->where('status', '!=', 'completed')->latest()->get();

        return view('dashboard', [
            'myGroups' => $myGroups,
            'myRequests' => $myRequests,
            'myDrivingTrips' => $myDrivingTrips,
        ]);
    }
}
