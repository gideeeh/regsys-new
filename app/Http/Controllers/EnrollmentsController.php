<?php

namespace App\Http\Controllers;

use App\Models\Academic_Year;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use App\Models\Subject;
use App\Services\AcademicYearService;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    protected $academicYearService;

    public function __construct(AcademicYearService $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }
    
    public function index (Request $request)
    {
        $query = Enrollment::query()
            ->select(
                'enrollments.enrollment_id', 
                'enrollments.academic_year', 
                'enrollments.term', 
                'enrollments.year_level',
                'enrollments.enrollment_date',
                'enrollments.enrollment_method',
                'enrollments.scholarship_type',
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
        $activeAcadYear = $this->academicYearService->determineActiveAcademicYear();
        if (!$activeAcadYear) {
            return redirect()->back()->with('error', 'No active academic year found.');
        }
        session(['active_academic_year' => $activeAcadYear->id]);
        $programs = Program::all();
        $subjects = Subject::all();
        $students = Student::all();
        $acad_years = Academic_Year::all();
        return view('admin.enroll-student',[
            'students' => $students,
            'programs' => $programs,
            'subjects' => $subjects,
            'acad_years' => $acad_years,
            'activeAcadYear' => $activeAcadYear,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'program_id' => 'required|exists:programs,program_id',
            'acad_year' => 'required|string|max:9',
            'term' => 'required|string|max:10',
            'year_level' => 'required|string|max:10',
            'enrollment_date' => 'sometimes|date',
            'scholarship_type' => 'sometimes|string|max:45',
            'status' => 'sometimes|string|max:45',
            'enrollment_method' => 'required|string|max:45',
            'selectedSubjects' => 'required|json',
        ]);

        $selectedSubjects = json_decode($request->selectedSubjects, true);

        $existingEnrollment = Enrollment::where([
            'student_id' => $validated['student_id'],
            'program_id' => $validated['program_id'],
            'academic_year' => $validated['acad_year'],
            'term' => $validated['term'],
        ])->first();

        if ($existingEnrollment) {
            \Log::warning("Duplicate enrollment attempt for student_id {$validated['student_id']} for academic year {$validated['acad_year']}, term {$validated['term']}.");
            return redirect()->back()->with('error', 'An enrollment record already exists for the selected academic year, term, and program.');
        }

        try {
            $enrollment = Enrollment::create([
                'student_id' => $validated['student_id'],
                'program_id' => $validated['program_id'],
                'academic_year' => $validated['acad_year'],
                'term' => $validated['term'],
                'year_level' => $validated['year_level'],
                'enrollment_date' => $validated['enrollment_date'] ?? now(),
                'scholarship_type' => $validated['scholarship_type'] ?? 'none',
                'status' => $validated['status'] ?? 'pending',
                'enrollment_method' => $validated['enrollment_method'],
            ]);

            session()->flash('selectedSubjects', $selectedSubjects);
            session()->flash('enrollment_id', $enrollment->enrollment_id);

            return view('admin.enrolled-subject');
        } catch (\Exception $e) {
            \Log::error('Enrollment creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error in enrolling the student.');
        }
    }
}
