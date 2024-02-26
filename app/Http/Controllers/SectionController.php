<?php

namespace App\Http\Controllers;

use App\Models\Academic_Year;
use App\Models\Program;
use App\Models\Section;
use App\Services\AcademicYearService;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected $academicYearService;

    public function __construct(AcademicYearService $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }
    public function index()
    {
        $acad_years = Academic_Year::all();
        $activeAcadYearAndTerm  = $this->academicYearService->determineActiveAcademicYearAndTerm();
        if (!$activeAcadYearAndTerm ) {
            return redirect()->back()->with('error', 'No active academic year or term found.');
        }
        $activeAcadYear = $activeAcadYearAndTerm['activeAcadYear'];
        $activeTerm = $activeAcadYearAndTerm['activeTerm'];
        session([
            'active_academic_year' => $activeAcadYear->id,
            'active_term' => $activeTerm,
        ]);

        $uniqueSections = Section::query()
            ->distinct('section_name')
            ->get(['section_name']);

        $programs = Program::all();
        return view('admin.sections', [
            'programs' => $programs,
            'acad_years' => $acad_years,
            'activeAcadYear' => $activeAcadYear,
            'activeTerm' => $activeTerm,
            'uniqueSections' => $uniqueSections,
        ]);
    }

    // public function create_section()
    // {
    //     $acad_years = Academic_Year::all();
    //     $programs = Program::all();
    //     $activeAcadYear = $this->academicYearService->determineActiveAcademicYear();
    //     if (!$activeAcadYear) {
    //         return redirect()->back()->with('error', 'No active academic year found.');
    //     }
    //     session(['active_academic_year' => $activeAcadYear->id]);
    //     return view('admin.create-section', [
    //         'programs' => $programs,
    //         'activeAcadYear' => $activeAcadYear,
    //         'acad_years' => $acad_years,
    //     ]);
    // }

    public function store(Request $request)
    {
        $section = Section::firstOrCreate([
            'section_name' => $request->section_name,
            'academic_year' => $request->academic_year,
            'term' => $request->term,
            'year_level' => $request->year_level,
        ]);

        if ($section->wasRecentlyCreated) {
            return redirect()->back()->with('success', 'Section successfully created.');
        } else {
            return redirect()->back()->with('error', 'Section already exists.');
        }
    }
}
