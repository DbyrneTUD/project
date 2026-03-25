<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Trip;
use App\Notifications\NewMessage;

class MessageController extends Controller
{
    public function show(Trip $trip)
    {
        $group = $trip->liftRequest->group;

        // only group members can access the trip chat
        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        // only the driver and requester of a trip can view and send messages
        if ($trip->driver_id !== auth()->id() && $trip->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$trip->liftRequest->id}");
        }

        // order messages by oldest first similar to messaging apps
        $messages = $trip->messages()->oldest()->get();

        return view('messages.show', [
            'trip' => $trip,
            'messages' => $messages,
        ]);
    }

    public function store(Trip $trip)
    {
        $group = $trip->liftRequest->group;

        if (! auth()->user()->groups->contains($group)) {
            return redirect('/groups');
        }

        if ($trip->driver_id !== auth()->id() && $trip->requester_id !== auth()->id()) {
            return redirect("/groups/{$group->id}/requests/{$trip->liftRequest->id}");
        }

        request()->validate([
            'message' => ['required', 'max:600'],
        ]);

        $message = Message::create([
            'trip_id' => $trip->id,
            'user_id' => auth()->id(),
            'message' => request('message'),
        ]);

        // when a message is sent notify other party
        if ($trip->driver_id === auth()->id()) {
            $trip->requester->notify(new NewMessage($message));
        } else {
            $trip->driver->notify(new NewMessage($message));
        }

        return redirect("/trips/{$trip->id}/messages");
    }
}
