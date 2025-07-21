<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Jobs\DeleteEventFromGoogle;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\GoogleCalendarService; // Import the GoogleCalendarService
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = Reminder::where('user_id', Auth::id())->orderByDesc('remind_at')->get();
        return inertia('Reminder/Reminder', [
            'reminders' => $reminders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        return Inertia::render('Reminder/create', [
            'title' => $request->query('title'),
            'url' => $request->query('url'),
            'id' => $request->query('id'),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReminderRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['video_id'] = $request->input('video_id', null);
        $reminder = Reminder::create($data);

        // Get the user's Google access token
        $accessToken = $request->user()->token;

        // if the user has connected their Google account
        if ($accessToken) {

            // Create an instance of the GoogleCalendarService
            $calendarService = new GoogleCalendarService($accessToken);

            // add  the reminder to Google Calendar
            $calendarService->createEvent($reminder);
        }

        // return redirect()->back();
        return redirect()->route('reminders.show', $reminder->id)
            ->with('success', 'Reminder Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {
        return Inertia::render('Reminder/ShowReminder', [
            'reminder' =>  $reminder
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder)
    {

        return Inertia::render('Reminder/EditReminder', [
            'reminder' =>  $reminder
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        if (Gate::allows('isOwner', $reminder)) {
            $reminder->update($request->validated());
            return  redirect()->back();
        }
        abort(403, 'You are not authorized to perform this Action.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Reminder $reminder)
    {
        $accessToken = $request->user()->token;
        $errmsg = "An error occurred while deleting from Google Calendar. Reminder was not deleted.";

        if (Gate::allows('isOwner', $reminder)) {

            // if google event id exsists in db delete it from google calender
            if ($reminder->google_event_id) {

                // for future use
                //DeleteGoogleCalanderEvent::dispatch($reminder->google_event_id, $accessToken);

                try {

                    $calendarService = new GoogleCalendarService($accessToken);
                    $eventdeleted = $calendarService->deleteEvent($reminder->google_event_id);

                    if ($eventdeleted) {
                        $reminder->delete();
                        return redirect()->back()->with('success', 'Reminder deleted successfully.');
                    }
                    return redirect()->back()->with('error', $errmsg);
                } catch (\Exception $e) {

                    Log::error('Failed to delete Google Calendar event: ' . $e->getMessage());
                    return redirect()->back()->with('error', $errmsg);
                }
            }

            // Delete the reminder from DB
            $reminder->delete();
            return redirect()->back();
        }
        abort(403, 'You are not authorized to perform this Action.');
    }
}
