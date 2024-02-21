<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;

class FacultyRecordsController extends Controller
{
    public function index(Request $request)
    {
        $query = Professor::query()
            ->select('professors.prof_id','professors.first_name', 'professors.last_name', 'professors.suffix', 'departments.dept_name')
            ->join('departments', 'professors.dept_id', '=', 'departments.dept_id');
    
        $searchTerm = $request->query('query');
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('professors.first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('professors.last_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('departments.dept_name', 'LIKE', "%{$searchTerm}%");
            });
        }
    
        $professorRecords = $query->paginate(10)->withQueryString(); 
    
        return view('admin.faculty-records', [
            'professors' => $professorRecords,
            'searchTerm' => $searchTerm 
        ]);
    }
    
    public function show($prof_id)
    {
        $professorRecord = Professor::findOrFail($prof_id);

        return view('admin.indiv-professor-record', ['professor' => $professorRecord]);
    }
}
