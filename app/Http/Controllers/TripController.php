<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class TripController extends Controller
{
    public function index()
    {
        // for the 'my trips page', show all trips where logged in user was either a driver or requester
        $trips = Trip::where('driver_id', auth()->id())->orWhere('requester_id', auth()->id())->get();

        return view('trips.index', [
            'trips' => $trips,
        ]);
    }

    public function show(Trip $trip)
    {
        $group = $trip->liftRequest->group;

        // only members of group can access trip page
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        // only driver or requester can access the trip page
        if ($trip->driver_id !== auth()->id() && $trip->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$trip->liftRequest->id}");
        }

        return view('trips.show', [
            'trip' => $trip,
            'group' => $group,
            'request' => $trip->liftRequest,
        ]);
    }
}
