<?php

namespace App\Http\Controllers;

use App\Models\Program_Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramSubjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,program_id', 
            'year' => 'required',
            'term' => 'required',
            'subject_ids' => 'sometimes|array',
            'subject_ids.*' => 'exists:subjects,subject_id' 
        ]);
        
        $programId = $request->program_id;
        $year = $request->year;
        $term = $request->term;
        
        $currentSubjectIds = Program_Subject::where('program_id', $programId)
                                             ->where('year', $year)
                                             ->where('term', $term)
                                             ->pluck('subject_id')->toArray();

        $submittedSubjectIds = $request->subject_ids ?? [];
    
        $subjectsToAdd = array_diff($submittedSubjectIds, $currentSubjectIds);
        
        $subjectsToRemove = array_diff($currentSubjectIds, $submittedSubjectIds);
    
        foreach ($subjectsToAdd as $subjectId) {
            Program_Subject::create([
                'program_id' => $programId,
                'subject_id' => $subjectId,
                'year' => $year,
                'term' => $term,
            ]);
        }

        if (!empty($subjectsToRemove)) {
            Program_Subject::where('program_id', $programId)
                           ->where('year', $year)
                           ->where('term', $term)
                           ->whereIn('subject_id', $subjectsToRemove)
                           ->delete();
        }
    
        Log::info('Request data:', $request->all());
        return back()->with('success', 'Subjects updated successfully.');
    }

    public function fetchSubjects(Request $request, $program_id, $year, $term)
    {
        $selectedSubjects = Program_Subject::where('program_id', $program_id)
                                        ->where('year', $year)
                                        ->where('term', $term)
                                        ->pluck('subject_id');

        return response()->json($selectedSubjects);
    }
}
