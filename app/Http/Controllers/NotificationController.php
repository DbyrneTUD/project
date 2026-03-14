<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {

        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications', [
            'notifications' => $notifications,
        ]);
    }
}
