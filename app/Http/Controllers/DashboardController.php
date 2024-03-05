<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ScrapingController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403); 
        }

        $chartController = new ChartController();
        $trendData = $chartController->enrollmentTrendsPerTerm();
        $programData = $chartController->enrollmentsByProgram();

        return view('admin.dashboard', [
            'trendData' => $trendData,
            'programData' => $programData,
        ]);
    }

    protected function scrapedNews()
    {
        $scrapingController = new ScrapingController();
        $news = $scrapingController->scrape();

        return response()->json($news);
    }

    public function getActiveClasses()
    {
        $now = Carbon::now();

        $activeF2FClasses = DB::table('section_subject_schedules as sss')
            ->join('section_subjects as ss', 'sss.sec_sub_id', '=', 'ss.id')
            ->join('sections as sec', 'ss.section_id', '=', 'sec.section_id')
            ->join('subjects as sub', 'ss.subject_id', '=', 'sub.subject_id')
            ->join('professors as prof', 'sss.prof_id', '=', 'prof.prof_id')
            ->select(
                'sub.subject_name',
                'sec.section_name',
                'prof.first_name',
                'prof.last_name',
                DB::raw("CONCAT(sss.start_time_f2f, ' - ', sss.end_time_f2f) as time_f2f")
            )
            ->where('sss.start_time_f2f','<=',$now)
            ->where('sss.end_time_f2f','>=',$now)
            ->get();

        $activeOnlineClasses = DB::table('section_subject_schedules as sss')
            ->join('section_subjects as ss', 'sss.sec_sub_id', '=', 'ss.id')
            ->join('sections as sec', 'ss.section_id', '=', 'sec.section_id')
            ->join('subjects as sub', 'ss.subject_id', '=', 'sub.subject_id')
            ->join('professors as prof', 'sss.prof_id', '=', 'prof.prof_id')
            ->select(
                'sub.subject_name',
                'sec.section_name',
                'prof.first_name',
                'prof.last_name',
                DB::raw("CONCAT(sss.start_time_online, ' - ', sss.end_time_online) as time_online")
            )
            ->where('sss.start_time_online','<=',$now)
            ->where('sss.end_time_online','>=',$now)
            ->get();

        $combinedClasses = [
            'activeF2FClasses' => $activeF2FClasses,
            'activeOnlineClasses' => $activeOnlineClasses
        ];
    
        return response()->json($combinedClasses);
    }

    public function calendarEvents()
    {
        $now = Carbon::now();
        $startOfDay = $now->startOfDay();
        $endOfDay = $now->endOfDay();
        $startOfWeek = $now->startOfWeek()->format('Y-m-d H:i:s');
        $endOfWeek = $now->endOfWeek()->format('Y-m-d H:i:s');
        $startOfMonth = $now->startOfMonth()->format('Y-m-d H:i:s');
        $endOfMonth = $now->endOfMonth()->format('Y-m-d H:i:s');

        $eventsToday = DB::table('calendar_events')
            ->whereBetween('start_time', [$startOfDay, $endOfDay])
            ->orWhereBetween('end_time', [$startOfDay, $endOfDay])
            ->get();

        $eventsThisWeek = DB::table('calendar_events')
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->orWhereBetween('end_time', [$startOfWeek, $endOfWeek])
            ->get();

        $eventsThisMonth = DB::table('calendar_events')
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->orWhereBetween('end_time', [$startOfMonth, $endOfMonth])
            ->get();

        $important = DB::table('calendar_events')
        ->where(function($query) {
            $query->where('title', 'like', '%exam%')
                    ->orWhere('title', 'like', '%enroll%');
        })
        ->get();

        return response()->json([
            'today' => $eventsToday,
            'this_week' => $eventsThisWeek,
            'this_month' => $eventsThisMonth,
            'important' => $important,
        ]);
    }

    public function enrollmentData()
    {
        $currentTermDetails = $this->getCurrentTermDetails();
        $academicYear = $currentTermDetails['academic_year'];
        $term = $currentTermDetails['term'];
    
        // Total Students Enrolled in the Current Term
        $totalStudentsEnrolledCurrentTerm = DB::table('enrollments')
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->count();
    
        // Enrollments Per Program for the Current Term
        $enrollmentsPerProgramCurrentTerm = DB::table('enrollments')
            ->join('programs', 'enrollments.program_id', '=', 'programs.program_id')
            ->where('enrollments.academic_year', $academicYear)
            ->where('enrollments.term', $term)
            ->select('programs.program_name', DB::raw('count(*) as total'))
            ->groupBy('programs.program_name')
            ->get();
    
        // Enrollments Per Year Level for the Current Term
        $enrollmentsPerYearLevelCurrentTerm = DB::table('enrollments')
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->select('year_level', DB::raw('count(*) as total'))
            ->groupBy('year_level')
            ->get();
    
        // Enrollments Per Program Per Year Level for the Current Term
        $enrollmentsPerProgramPerYearLevelCurrentTerm = DB::table('enrollments')
            ->join('programs', 'enrollments.program_id', '=', 'programs.program_id')
            ->where('enrollments.academic_year', $academicYear)
            ->where('enrollments.term', $term)
            ->select('programs.program_name', 'enrollments.year_level', DB::raw('count(*) as total'))
            ->groupBy('programs.program_name', 'enrollments.year_level')
            ->get();
    
        return [
            'totalStudentsEnrolledCurrentTerm' => $totalStudentsEnrolledCurrentTerm,
            'enrollmentsPerProgramCurrentTerm' => $enrollmentsPerProgramCurrentTerm,
            'enrollmentsPerYearLevelCurrentTerm' => $enrollmentsPerYearLevelCurrentTerm,
            'enrollmentsPerProgramPerYearLevelCurrentTerm' => $enrollmentsPerProgramPerYearLevelCurrentTerm,
        ];
    }

    private function getCurrentTermDetails()
    {
        $today = Carbon::now();

        // Fetch the current academic year based on today's date. Adjust the logic if your academic year spans two calendar years
        $currentAcademicYear = DB::table('academic_years')
            ->where('term_1_start', '<=', $today)
            ->where('term_3_end', '>=', $today)
            ->first();

        if (!$currentAcademicYear) {
            return null; 
        }

        $currentTerm = null;
        if ($today->between($currentAcademicYear->term_1_start, $currentAcademicYear->term_1_end)) {
            $currentTerm = 'Term 1';
        } elseif ($today->between($currentAcademicYear->term_2_start, $currentAcademicYear->term_2_end)) {
            $currentTerm = 'Term 2';
        } elseif ($today->between($currentAcademicYear->term_3_start, $currentAcademicYear->term_3_end)) {
            $currentTerm = 'Term 3';
        }

        return [
            'academic_year' => $currentAcademicYear->acad_year,
            'term' => $currentTerm,
        ];
    }
}
