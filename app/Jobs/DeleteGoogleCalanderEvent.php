<?php

namespace App\Jobs;

use App\Services\GoogleCalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DeleteGoogleCalanderEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */

    protected string $google_event_id;
    protected $accessToken;
    public function __construct(string $google_event_id ,$accessToken)
    {
        $this->google_event_id = $google_event_id;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $calendarService = new GoogleCalendarService($this->accessToken);
            $calendarService->deleteEvent($this->google_event_id);
        } catch (\Exception $e) {
            Log::warning("Failed to delete Google Calendar event: " . $e->getMessage());
        }
    }
}
