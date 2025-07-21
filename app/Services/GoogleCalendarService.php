<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{

    // This will be used to talk to Google Calendar
    protected $client;
    protected $accessToken;

    public function __construct($accessToken)
    {
        // Set up the connection to Google Calendar API
        $this->client = new Client([
            'base_uri' => 'https://www.googleapis.com/calendar/v3/',
        ]);
        $this->accessToken = $accessToken;
    }

    // This function creates an event in Google Calendar using the reminder details
    public function createEvent($reminder)
    {
        $youtube_baseurl = 'https://www.youtube.com/watch?v=';

        // Convert the reminder time to the format google calendar expects
        $startDateTime = Carbon::parse($reminder->remind_at)->toIso8601String();

        // Set the event to last 20 minutes
        $endDateTime = Carbon::parse($reminder->remind_at)->addMinutes(20)->toIso8601String();


        $event = [
            'summary' => 'WatchLater: ' . $reminder->title,
            'description' => 'Video URL: ' . ENV('YOUTUBE_BASEURL') . $reminder->url,

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
                    ['method' => 'email', 'minutes' => 20],
                ],
            ],
        ];

        try {
            $response = $this->client->post('calendars/primary/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $event,
            ]);

            $data = json_decode($response->getBody(), true);
            $reminder->google_event_id = $data['id'] ? $data['id'] : null;
            $reminder->save();

        } catch (\Exception $e) {

            Log::error('Google Calendar Event Creation Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     *  Deletes the Event id from google calender .
     *  If event was deleted via google it will also  be
     *  treated as success so we can delete locally
     *
     */

    public function deleteEvent($google_event_id)
    {

        try {
            $this->client->delete("calendars/primary/events/{$google_event_id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            return true;

        } catch (\Exception $e) {

            $code = $e->getCode();
            // If it's a 404 the event id doesn't exist in Google Calendar anymore
            if (in_array($code, [404, 410])) {

                Log::warning("Google Calendar event already deleted: {$google_event_id}");
                return true;
            }

            Log::error('Google Calendar delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
