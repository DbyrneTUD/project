<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\LiftRequest;
use App\Models\Trip;
use App\Notifications\LiftAccepted;
use App\Notifications\LiftRequested;
use Illuminate\Support\Facades\Notification;

class LiftRequestController extends Controller
{
    public function create(Group $group)
    {

        // only group members can create requests in each group
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        return view('requests.create', [
            'group' => $group,
        ]);
    }

    public function store(Group $group)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        request()->validate([
            'origin' => ['required', 'min:2'],
            'destination' => ['required', 'min:2'],
            'earliest_departure' => ['required', 'date'],
            'latest_departure' => ['required', 'date'],
        ]);

        $liftRequest = LiftRequest::create([
            'group_id' => $group->id,
            'requester_id' => auth()->id(),
            'origin' => request('origin'),
            'destination' => request('destination'),
            'earliest_departure' => request('earliest_departure'),
            'latest_departure' => request('latest_departure'),
            'status' => 'open',
        ]);

        // when a new request is created, notify all members in group
        Notification::send($group->members, new LiftRequested($liftRequest));

        return redirect("/groups/{$group->id}");
    }

    public function show(Group $group, LiftRequest $liftRequest)
    {
        // only group members can view a request
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        return view('requests.show', [
            'group' => $group,
            'request' => $liftRequest,
        ]);
    }

    public function edit(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        // only the user who created the request can edit it
        if ($liftRequest->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}");
        }

        return view('requests.edit', [
            'group' => $group,
            'request' => $liftRequest,
        ]);
    }

    public function update(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        if ($liftRequest->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}");
        }

        request()->validate([
            'origin' => ['required', 'min:2'],
            'destination' => ['required', 'min:2'],
            'earliest_departure' => ['required', 'date'],
            'latest_departure' => ['required', 'date'],
        ]);

        $liftRequest->update([
            'origin' => request('origin'),
            'destination' => request('destination'),
            'earliest_departure' => request('earliest_departure'),
            'latest_departure' => request('latest_departure'),
        ]);

        return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");

    }

    public function destroy(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        if ($liftRequest->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}");
        }

        $liftRequest->delete();

        return redirect("/groups/{$group->id}");
    }

    public function accept(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        // the requester cannot accept their own request
        if ($liftRequest->requester_id === auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        // if a trip exists for this request it cannot be accepted again
        if ($liftRequest->trip) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        // create a trip for this request once it has been accepted
        Trip::create([
            'lift_request_id' => $liftRequest->id,
            'driver_id' => auth()->id(),
            'requester_id' => $liftRequest->requester_id,
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // update status of request
        $liftRequest->update([
            'status' => 'accepted',
        ]);

        // notify the requester that their request has been accepted
        $liftRequest->requester->notify(new LiftAccepted($liftRequest));

        return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
    }

    public function cancel(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        // only the driver and requester can cancel
        if ($liftRequest->trip->driver_id !== auth()->id() && $liftRequest->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        if (! $liftRequest->trip) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        $liftRequest->trip->delete();

        $liftRequest->update([
            'status' => 'open',
        ]);

        return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
    }

    public function complete(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        if (! $liftRequest->trip) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        // only the requester can complete a trip
        if ($liftRequest->trip->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        // keep both request and trip status in sync
        $liftRequest->update([
            'status' => 'completed',
        ]);

        $liftRequest->trip->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect("/trips/{$liftRequest->trip->id}");
    }
}
