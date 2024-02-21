<?php

namespace App\Http\Controllers;

use App\Models\Calendar_Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $events = Calendar_Event::all()->map(function ($event) {
            return [
                'title' => $event->title,
                'comments' => $event->comments,
                'start' => $event->start_time->format('Y-m-d H:i:s'), // Adjust format as necessary
                'end' => $event->end_time->format('Y-m-d H:i:s'),
            ];
        });

        return view('admin.academic-calendar', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'comments' => 'sometimes|string', 
        ]);
    
        $event = Calendar_Event::create([
            'title' => $validated['title'],
            'start_time' => $validated['start'],
            'end_time' => $validated['end'],
            'comments' => $validated['comments'],
        ]);
    
        return response()->json($event, 200);
    }
    
}

