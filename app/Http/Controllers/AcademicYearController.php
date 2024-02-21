<?php

namespace App\Http\Controllers;

use App\Models\Academic_Year;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $acad_years = Academic_Year::all();
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'acad_year' => 'required|unique:academic_years,acad_year',
            'acad_year_start' => 'required',
            'acad_year_end' => 'required',
            'term_1_start' => 'required',
            'term_1_end' => 'required',
            'term_2_start' => 'required',
            'term_2_end' => 'required',
            'term_3_start' => 'required',
            'term_3_end' => 'required',
        ]);

        $acad_year = new Academic_Year($validated);
        $acad_year->acad_year = $request->acad_year;
        $acad_year->acad_year_start = $request->acad_year_start;
        $acad_year->acad_year_end = $request->acad_year_end;
        $acad_year->term_1_start = $request->term_1_start;
        $acad_year->term_1_end = $request->term_1_end;
        $acad_year->term_2_start = $request->term_2_start;
        $acad_year->term_2_end = $request->term_2_end;
        $acad_year->term_3_start = $request->term_3_start;
        $acad_year->term_3_end = $request->term_3_end;
        $acad_year->save();
        return redirect()->back()->with('success', 'Acad year has been set!');
    }
}
