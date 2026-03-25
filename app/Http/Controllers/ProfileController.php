<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if (request()->hasFile('photo')) {
            $photoPath = request()->file('photo')->store('photos', 'public');
            $request->user()->photo_path = $photoPath;
        }

        $request->user()->bio = $request->bio;

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user)
    {

        // count trips where user was a driver
        $liftsGiven = Trip::where('driver_id', $user->id)->whereNotNull('completed_at')->count();
        // count trips where user was a passenger
        $liftsTaken = Trip::where('requester_id', $user->id)->whereNotNull('completed_at')->count();

        // get review information such as count, average rating and recent reviews for profile page
        $reviewsReceivedCount = $user->reviewsReceived()->count();
        $avgRating = $user->reviewsReceived()->avg('rating');
        $reviews = $user->reviewsReceived()->latest()->paginate(10);


        return view('profile.show', [
            'user' => $user,
            'liftsGiven' => $liftsGiven,
            'liftsTaken' => $liftsTaken,
            'reviewsReceivedCount' => $reviewsReceivedCount,
            'avgRating' => $avgRating,
            'reviews' => $reviews,
        ]);
    }
}
