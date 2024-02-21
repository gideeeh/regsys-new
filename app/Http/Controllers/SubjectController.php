<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index() {
        $subjects = Subject::with(['prerequisite1', 'prerequisite2', 'prerequisite3'])
            ->get();
            
        return view('subjects', ['subjects' => $subjects]);
    }

    public function search(Request $request)
    {
        $search = $request->get('q');

        $subjects = Subject::where('subject_name','like','%'.$search.'%')
                            ->orWhere('subject_code','like','%'.$search.'%')
                            ->get([
                                'subject_id as subject_id',
                                'subject_name as subject_name',
                                'subject_code as subject_code',
                            ]);
        return response()->json($subjects);
    }
}
