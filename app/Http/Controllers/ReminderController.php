<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReminderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        Reminder::create($data);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {

        return Inertia::render('Reminder/ShowReminder',[
            'reminder' =>  $reminder
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder)
    {

        return Inertia::render('Reminder/EditReminder',[
            'reminder' =>  $reminder
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReminderRequest $request,Reminder $reminder)
    {
         if(Gate::allows('isOwner',$reminder))
        {
         $reminder->update($request->validated());
         redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder)
    {

        if(!Gate::allows('isOwner',$reminder))
        {
              abort(403);
        }
        $reminder->delete();
        return Redirect()->back();
    }
}
