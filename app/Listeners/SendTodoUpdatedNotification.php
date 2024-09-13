<?php

namespace App\Listeners;

use App\Events\TodoUpdated;
use App\Mail\TodoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendTodoUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TodoUpdated $event): void
    {
        // Get the authenticated user, then send the mail notification.
        $user = Auth::user();
        if ($user) {
            Mail::to($user->email)->send(new TodoNotification($event->todo, 'updated'));
        }
    }
}
