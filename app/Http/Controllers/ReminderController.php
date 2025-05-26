<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ReminderRequest;
use Illuminate\Support\Facades\Auth;

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
            $title = $request->query('title');
            $url = $request->query('url');
        return Inertia::render('Reminder/create',[
            'title' => $title,
            'url' => $url,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function reminders()
    {
        $reminders = Reminder::where('user_id', Auth::id())->orderByDesc('remind_at')->get();
        return inertia('Reminder/Reminder', [
            'reminders' => $reminders,
        ]);
    }
}
