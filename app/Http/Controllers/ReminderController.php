<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\GoogleCalendarService; // Import the GoogleCalendarService

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

        // Get the user's Google access token.
        $accessToken = $request->user()->token;

        // If the user has connected their Google account
        if ($accessToken) {

            // Create an instance of the GoogleCalendarService with the access token
            $calendarService = new GoogleCalendarService($accessToken);

            // Add the reminder to Google Calendar
            $calendarService->createEvent($reminder);
        }

        return redirect()->back();
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
    public function destroy(Reminder $reminder)
    {

        if (Gate::allows('isOwner', $reminder)) {
            $reminder->delete();
            return Redirect()->back();
        }
        abort(403, 'You are not authorized to perform this Action.');
    }
}
