<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function index (Request $request)
    {
        $query = Enrollment::query()
            ->select(
                'enrollments.enrollment_id', 
                'enrollments.academic_year', 
                'enrollments.term', 
                'enrollments.year_level',
                'enrollments.is_Continuing',
                'enrollments.enrollment_date',
                'enrollments.status',
                'students.student_number',
                'students.first_name', 
                'students.middle_name', 
                'students.last_name',
                'students.suffix',
                'programs.program_code',
            )
            ->join('programs', 'programs.program_id', '=', 'enrollments.program_id')
            ->join('students', 'enrollments.student_id', '=', 'students.student_id');
            
        $searchTerm = $request->query('query');
        if($searchTerm)
        {
            $query->where(function($query) use ($searchTerm) {
                $query->where('students.first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('students.last_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('students.student_number', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('programs.program_code', 'LIKE', "%{$searchTerm}%");
            });
        }

        $enrollmentRecords = $query->paginate(10)->withQueryString();

        return view('admin.enrollment-records', [
            'enrollments' => $enrollmentRecords,
            'searchTerm' => $searchTerm
        ]);
    }

    public function show($enrollment_id)
    {
        $enrollmentRecord = Enrollment::findOrFail($enrollment_id);
        
        return view('admin.indiv-enrollment-record', ['enrollment' => $enrollmentRecord]);
    }

    public function enroll()
    {
        $programs = Program::all();
        $students = Student::all();
        return view('admin.enroll-student',[
            'students' => $students,
            'programs' => $programs,
        ]);
    }
    
}
