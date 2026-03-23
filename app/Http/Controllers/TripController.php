<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::where('driver_id', auth()->id())->orWhere('requester_id', auth()->id())->get();

        return view('trips.index', [
            'trips' => $trips,
        ]);
    }

    public function show(Trip $trip)
    {
        $group = $trip->liftRequest->group;

        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

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
