<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Trip;
use App\Notifications\NewReview;

class ReviewController extends Controller
{
    public function create(Trip $trip)
    {
        // only the requester can leave a review
        if ($trip->requester_id !== auth()->id()) {
            return redirect('/trips');
        }

        // a review can only be left after a trip has been completed
        if (! $trip->completed_at) {
            return redirect("/trips/{$trip->id}");
        }

        // only one review can be left on a trip
        if ($trip->review) {
            return redirect("/trips/{$trip->id}");
        }

        return view('reviews.create', [
            'trip' => $trip,
        ]);
    }

    public function store(Trip $trip)
    {
        if ($trip->requester_id !== auth()->id()) {
            return redirect('/trips');
        }

        if (! $trip->completed_at) {
            return redirect("/trips/{$trip->id}");
        }

        if ($trip->review) {
            return redirect("/trips/{$trip->id}");
        }

        request()->validate([
            'rating' => ['required', 'min:1', 'max:5'],
            'comment' => ['nullable', 'max:800'],
        ]);

        $review = Review::create([
            'reviewer_id' => auth()->id(),
            'trip_id' => $trip->id,
            'driver_id' => $trip->driver_id,
            'rating' => request('rating'),
            'comment' => request('comment'),
        ]);

        // notify the driver after a review is submitted for a trip they drove
        $trip->driver->notify(new NewReview($review));

        return redirect("/trips/{$trip->id}");
    }

    public function reply(Trip $trip)
    {

        // only reply if a review has been posted on trip
        if (! $trip->review) {
            return redirect("/trips/{$trip->id}");
        }

        // only a driver can reply to a review
        if ($trip->driver_id !== auth()->id()) {
            return redirect('/trips');
        }

        // only one reply can be left on a review
        if ($trip->review->reply) {
            return redirect("/profile/{$trip->driver_id}");
        }

        request()->validate([
            'reply' => ['required', 'max:800'],
        ]);

        $trip->review->update([
            'reply' => request('reply'),
        ]);

        return redirect("/profile/{$trip->driver_id}");
    }
}
