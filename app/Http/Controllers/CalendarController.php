<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Calendar/Index', [
            'calendar' => Calendar::where('exists', true)->with('users')->orderBy('date', 'desc')->orderBy('time', 'desc')->simplePaginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Calendar/Create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|max:55',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string|max:1500',
        ]);

        $user = $request->user();

        $event = Calendar::create([
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'user_id' => $user->id,
            'exists' => true,
        ]);

        return Redirect::route('calendar.index')->with('message', 'L\'événement a bien été créé.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        return Inertia::render('Calendar/Show', [
            'event' => Calendar::where('id', $calendar->id)->with('users')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        $validated = $request->validate([
            'name' => 'string|max:55',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string|max:1500',
        ]);

        $updateCalendar = $calendar->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
        ]);

        return Redirect::route('calendar.index')->with('message', 'L\'événemenet a été mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        $calendar->update([
            'exists' => false,
        ]);

        return Redirect::route('calendar.index')->with('message', 'L\'événement a été supprimé.');
    }
}
