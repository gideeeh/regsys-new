<?php

namespace App\Http\Controllers;

use App\Models\SectionSubjectSchedule;
use Illuminate\Http\Request;

class SectionSubjectSchedulesController extends Controller
{
    public function sec_sub_schedule_json()
    {
        $sec_sub_schedules = SectionSubjectSchedule::all();
        return response()->json($sec_sub_schedules);
    }
}
