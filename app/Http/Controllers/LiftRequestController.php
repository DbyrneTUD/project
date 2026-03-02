<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\LiftRequest;
use App\Models\Trip;

class LiftRequestController extends Controller
{
    public function create(Group $group)
    {
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

        LiftRequest::create([
            'group_id' => $group->id,
            'requester_id' => auth()->id(),
            'origin' => request('origin'),
            'destination' => request('destination'),
            'earliest_departure' => request('earliest_departure'),
            'latest_departure' => request('latest_departure'),
            'status' => 'open',
        ]);

        return redirect("/groups/{$group->id}");
    }

    public function show(Group $group, LiftRequest $liftRequest)
    {
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

        if ($liftRequest->requester_id === auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        if ($liftRequest->trip) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        Trip::create([
            'lift_request_id' => $liftRequest->id,
            'driver_id' => auth()->id(),
            'requester_id' => $liftRequest->requester_id,
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $liftRequest->update([
            'status' => 'accepted',
        ]);

        return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
    }

    public function cancel(Group $group, LiftRequest $liftRequest)
    {
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

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

        if ($liftRequest->trip->driver_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
        }

        $liftRequest->update([
            'status' => 'completed',
        ]);

        $liftRequest->trip->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect("/groups/{$group->id}/requests/{$liftRequest->id}");
    }
}
