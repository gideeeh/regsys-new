<?php

namespace App\Http\Controllers;

use App\Models\SectionSubject;
use App\Models\SectionSubjectSchedule;
use Illuminate\Http\Request;

class SectionSubjectsController extends Controller
{
    public function store(Request $request) {
        $section_subjects = SectionSubject::firstOrCreate([
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
        ]);
    
        if ($section_subjects->wasRecentlyCreated) {
            $class_days_online = is_array($request->online_days) ? json_encode($request->online_days) : $request->online_days;
            $class_days_f2f = is_array($request->f2f_days) ? json_encode($request->f2f_days) : $request->f2f_days;
    
            $section_subject_schedules = SectionSubjectSchedule::firstOrCreate([
                'sec_sub_id' => $section_subjects->id,
                'prof_id' => $request->prof_id,
                'class_days_f2f' => $class_days_f2f,
                'class_days_online' => $class_days_online,
                'start_time_f2f' => $request->start_time_f2f, 
                'end_time_f2f' => $request->end_time_f2f,
                'start_time_online' => $request->start_time_online,
                'end_time_online' => $request->end_time_online,
                'room' => $request->room,
                'class_limit' => $request->class_limit,
            ]);
    
            if ($section_subject_schedules->wasRecentlyCreated) {
                return redirect()->back()->with('success', 'Section successfully created.');
            } else {
                return redirect()->back()->with('error', 'Section already exists.');
            }
        } else {
            return redirect()->back()->with('error', 'Section already exists.');
        }
    }
    
}
