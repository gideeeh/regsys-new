<?php

namespace App\Http\Controllers;

use App\Models\Academic_Year;
use App\Models\Program;
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
        $activeAcadYear = $this->academicYearService->determineActiveAcademicYear();
        if (!$activeAcadYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }
        session(['active_academic_year' => $activeAcadYear->id]);
        $programs = Program::all();
        return view('admin.sections', [
            'programs' => $programs,
            'acad_years' => $acad_years,
            'activeAcadYear' => $activeAcadYear,
        ]);
    }

    public function create_section()
    {
        $acad_years = Academic_Year::all();
        $programs = Program::all();
        $activeAcadYear = $this->academicYearService->determineActiveAcademicYear();
        if (!$activeAcadYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }
        session(['active_academic_year' => $activeAcadYear->id]);
        return view('admin.create-section', [
            'programs' => $programs,
            'activeAcadYear' => $activeAcadYear,
            'acad_years' => $acad_years,
        ]);
    }
}
