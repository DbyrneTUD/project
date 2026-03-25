<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {

        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        // when notifications page is opened mark all unread notifications as read
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications', [
            'notifications' => $notifications,
        ]);
    }
}
