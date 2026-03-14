<?php

namespace App\Http\Controllers;

use App\Models\Trip;

class TripController extends Controller
{
    public function show(Trip $trip)
    {
        $group = $trip->liftRequest->group;

        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        return view('trips.show', [
            'trip' => $trip,
            'group' => $group,
            'request' => $trip->liftRequest,
        ]);
    }
}
