<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $reminder;

    public function __construct($reminder)
    {
      $this->reminder = $reminder;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'WatchList Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'watchlistreminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }


    public function build()
    {
        $videoId = $this->reminder->url;
        return $this->markdown('watchlistreminder')
                    ->with([
                        'title' => $this->reminder->title,
                        'user' => $this->reminder->user->name,
                        'url' => 'https://www.youtube.com/watch?v=' . $videoId,
                        'editReminderid' => route('reminders.edit', $this->reminder->id),
                    ]);
    }

}
