<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectCatalogController extends Controller
{
    public function index(Request $request)
    {
        // Corrected typo: changed 'sj' to 's' in join conditions
        $subjectsQuery = DB::table('subjects as s')
            ->leftJoin('subjects as pr1', 's.prerequisite_1', '=', 'pr1.subject_id')
            ->leftJoin('subjects as pr2', 's.prerequisite_2', '=', 'pr2.subject_id') 
            ->select(
                's.subject_id', 
                's.subject_code',
                's.subject_name',
                's.subject_description',
                's.units_lec',
                's.units_lab',
                'pr1.subject_name as prereq1',
                'pr2.subject_name as prereq2'
            ); 

        // Search
        $searchTerm = $request->query('query');
        if ($searchTerm) {
            $subjectsQuery->where(function ($query) use ($searchTerm) {
                $query->where('s.subject_code', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('s.subject_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $subjects = $subjectsQuery->paginate(10)->withQueryString();
        $all_subjects = Subject::orderBy('subject_name')->get();

        return view('admin.subject-catalog', [
            'subjects' => $subjects,
            'all_subjects' => $all_subjects,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code',
            'subject_name' => 'required|unique:subjects,subject_name',
            'units_lec' => 'required|numeric',
            'units_lab' => 'required|numeric',
        ]);

        $subject = new Subject($validated);
        $subject->subject_code = $request->subject_code;
        $subject->subject_name = $request->subject_name;
        $subject->subject_description = $request->subject_description;
        $subject->units_lec = $request->units_lec;
        $subject->units_lab = $request->units_lab;
        $subject->prerequisite1 = $request->prereq1;
        $subject->prerequisite2 = $request->prereq2;
        $subject->save();
    
        return redirect()->back()->with('success', 'Program added successfully!');
    }
}
