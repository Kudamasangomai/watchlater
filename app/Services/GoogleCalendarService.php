<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{

    protected $client;
    protected $accessToken;

    public function __construct($accessToken)
    {
        $this->client = new Client([
            'base_uri' => 'https://www.googleapis.com/calendar/v3/',
        ]);
        $this->accessToken = $accessToken;
    }
     public function createEvent($reminder)
    {
        $startDateTime = Carbon::parse($reminder->reminder_time)->toIso8601String();
        $endDateTime = Carbon::parse($reminder->reminder_time)->addMinutes(30)->toIso8601String();

        $event = [
            'summary' => 'WatchLater: ' . $reminder->title,
            'description' => 'Video URL: ' . $reminder->url,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => 'Africa/Harare',
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => 'Africa/Harare',
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

            // Optionally save event ID from Google for later update/delete
            return $data['id'] ?? null;

        } catch (\Exception $e) {
            // Handle errors (token expired, invalid, etc.)
            Log::error('Google Calendar Event Creation Failed: ' . $e->getMessage());
            return null;
        }
    }


}