<?php

namespace App\Console\Commands;

use App\Mail\ReminderEmail;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders at the scheduled time for each user';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Gets the current date and time .
        $now = Carbon::now();

        /**
         * Makes a copy of the current time, then subtracts 2 minutes.
         * So if now is 12:00, then windowStart is 11:58
         */
        $windowStart = $now->copy()->subMinutes(5);


        /**
         * Get all reminders that: Have not been sent yet and
         * Have a remind_at time that falls within the last 2 minutes
         * Eager loads the user relationship (for access to email).
         */
        $reminders = Reminder::whereNull('sent_at')
             ->whereBetween('remind_at', [$windowStart, $now])
            ->with('user')
            ->get();

        foreach ($reminders as $reminder) {
            Mail::to($reminder->user->email)->send(new ReminderEmail($reminder));
            $reminder->sent_at = $now;
            $reminder->save();
        }
    }
}
