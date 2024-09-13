<?php

namespace App\Mail;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TodoNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $todo;
    public $action;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Todo $todo, $action = 'created')
    {
        $this->todo = $todo;
        $this->action = $action;
        $this->user = Auth::user();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Set the subject depending on the action.
        $subject = $this->action == 'created' ? 'New Todo Created' : 'Todo Updated';

        return $this->subject($subject)
            ->view('emails.todo-notification')
            ->with([
                'title' => $this->todo->title,
                'description' => $this->todo->description,
                'userName' => $this->user ? $this->user->name : 'User',
                'action' => $this->action,
            ]);
    }
}
