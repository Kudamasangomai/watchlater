<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{

    protected $client; // This will be used to talk to Google Calendar
    protected $accessToken; // This is like a password to access your Google Calendar

    public function __construct($accessToken)
    {
        // Set up the connection to Google Calendar API
        $this->client = new Client([
            'base_uri' => 'https://www.googleapis.com/calendar/v3/',
        ]);
        $this->accessToken = $accessToken; // Save the access token for later use
    }

    // This function creates an event in Google Calendar using the reminder details
    public function createEvent($reminder)
    {
        $youtube = 'https://www.youtube.com/watch?v=';
        // Convert the reminder time to the format Google Calendar expects
        $startDateTime = Carbon::parse($reminder->reminder_time)->toIso8601String();

        // Set the event to last 30 minutes
        $endDateTime = Carbon::parse($reminder->reminder_time)->addMinutes(30)->toIso8601String();

        // Prepare the event details for Google Calendar
        $event = [
            'summary' => 'WatchLater: ' . $reminder->title,
            'description' => 'Video URL: ' . $youtube.$reminder->url,

            // When the event starts and ends
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => 'Africa/Harare',
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => 'Africa/Harare',
            ],
            // Add custom reminders (notifications)
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                     // Popup 5 minutes before
                    ['method' => 'popup', 'minutes' => 5],
                    // You can add more, e.g. email:
                    // ['method' => 'email', 'minutes' => 30],
                ],
            ],
        ];

        try {
            // Send the event to Google Calendar
            $response = $this->client->post('calendars/primary/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken, // Use your access token
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $event,
            ]);

            $data = json_decode($response->getBody(), true);

            // Optionally save event ID from Google for later update/delete
            // Return the event ID if successful
            return $data['id'] ?? null;

        } catch (\Exception $e) {
            // If something goes wrong, log the error for debugging
            Log::error('Google Calendar Event Creation Failed: ' . $e->getMessage());
            return null;
        }
    }

}
