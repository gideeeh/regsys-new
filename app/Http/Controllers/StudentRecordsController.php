<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PHPUnit\Framework\MockObject\Builder\Stub;

class StudentRecordsController extends Controller
{
    public function index(Request $request)
    {
        $studentsQuery = DB::table('students as s')
            ->leftJoin('enrollments as e', 's.student_id', '=', 'e.student_id')
            ->leftJoin('programs as p', 'e.program_id', '=', 'p.program_id') 
            ->select(
                's.student_id',
                's.student_number',
                's.first_name',
                's.middle_name',
                's.last_name',
                's.suffix',
                'p.program_code',
                DB::raw('MAX(e.year_level) as year_level')
            )
            ->groupBy('s.student_id', 's.student_number', 's.first_name', 's.middle_name', 's.last_name', 's.suffix', 'p.program_code');

        $searchTerm = $request->query('query');
        if ($searchTerm) {
            $studentsQuery->where(function ($query) use ($searchTerm) {
                $query->where('s.first_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('s.last_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('s.student_number', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('p.program_code', 'LIKE', "%{$searchTerm}%");
            });
        }

        $studentRecords = $studentsQuery->paginate(10)->withQueryString();

        return view('admin.student-records', [
            'students' => $studentRecords,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function edit($student_id)
    {
        $student = Student::findOrFail($student_id);
        $years = [];
        $currentYear = date('Y');
        for ($year = $currentYear; $year >= $currentYear - 40; $year--) {
            $years[$year] = $year;
        }
                return view('admin.edit-student', [
            'student' => $student,
            'years' => $years,
        ]);
    }

    public function update_personal(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
    
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            // Add other fields as necessary
        ]);
    
        $student->update($validatedData);
    
        return redirect()->route('student-records.show', ['student' => $student->student_id])->with('success', 'Student information updated successfully.');
    }
    
    public function show($student_id)
    {
        $latestEnrollment = Student::with(['latestEnrollment', 'latestEnrollment.program', 'notes'])->findOrFail($student_id);
        $student = Student::findOrFail($student_id);
        // $latestYearLevel = $student->enrollments()->orderByDesc('year_level')->first()->year_level ?? null;
        $enrollmentDetails = DB::table('enrollments as e')
        ->join('students as s', 'e.student_id', '=', 's.student_id')
        ->join('enrolled_subjects as esj', 'e.enrollment_id', '=', 'esj.enrollment_id')
        ->join('subjects as sj', 'esj.subject_id', '=', 'sj.subject_id')
        ->leftJoin('subjects as pr1', 'sj.prerequisite_1', '=', 'pr1.subject_id')
        ->leftJoin('subjects as pr2', 'sj.prerequisite_2', '=', 'pr2.subject_id')
        ->select(
            's.student_id',
            's.student_number',
            'sj.subject_code',
            'sj.subject_name',
            'pr1.subject_name as Prerequisite_Name_1',
            'pr2.subject_name as Prerequisite_Name_2',
            'e.year_level',
            'e.term',
            'sj.units_lec',
            'sj.units_lab',
            DB::raw('(sj.units_lec + sj.units_lab) AS TOTAL'),
            'esj.final_grade'
        )
        ->where('s.student_id', $student_id)
        ->orderByDesc('e.year_level')
        ->orderByDesc('e.term')
        ->orderBy('sj.subject_name')
        ->get();
    
        return view('admin.indiv-student-record', [
            'student' => $student,
            'latestEnrollment' => $latestEnrollment,
            'enrollmentDetails' => $enrollmentDetails
        ]);
    }

    public function student_json(Request $request)
    {
        $search = $request->get('q');

        $students = Student::where('first_name','like','%'.$search.'%')
                            ->orWhere('last_name','like','%'.$search.'%')
                            ->orWhere('student_number','like','%'.$search.'%')
                            ->get([
                                'student_id as student_id',
                                'first_name as first_name',
                                'middle_name as middle_name',
                                'last_name as last_name',
                                'student_number as student_number',
                                'personal_email as personal_email',
                                'phone_number as phone_number',
                            ]);
        return response()->json($students);    
    }

    public function fetch_student_json($student_id)
    {
        $student = Student::findOrFail($student_id);
        return response()->json([
            'personal_email' => $student->personal_email,
            'phone_number' => $student->phone_number,
            'first_name' => $student->first_name,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
           'student_number' => 'required|unique:students,student_number',
           'first_name' => 'required',
           'last_name' => 'required',
           'personal_email' => 'required|unique:students,personal_email',
        ]);

        $isTransferee = $request->has('is_transferee') ? true : false;
        $isIrregular = $request->has('is_irregular') ? true : false;

        $new_student = new Student($validated);
        $new_student->student_number = $request->student_number;
        $new_student->first_name = $request->first_name;
        $new_student->middle_name = $request->middle_name;
        $new_student->last_name = $request->last_name;
        $new_student->suffix = $request->suffix;
        $new_student->sex = $request->sex;
        $new_student->birthdate = $request->birthdate;
        $new_student->birthplace = $request->birthplace;
        $new_student->civil_status = $request->civil_status;
        $new_student->nationality = $request->nationality;
        $new_student->religion = $request->religion;
        $new_student->phone_number = $request->phone_number;
        $new_student->personal_email = $request->personal_email;
        $new_student->school_email = $request->school_email;
        $new_student->house_num = $request->house_num;
        $new_student->street = $request->street;
        $new_student->brgy = $request->brgy;
        $new_student->city_municipality = $request->city_municipality;
        $new_student->province = $request->province;
        $new_student->zipcode = $request->zipcode;
        $new_student->guardian_name = $request->guardian_name;
        $new_student->guardian_contact = $request->guardian_contact;
        $new_student->elementary = $request->elementary;
        $new_student->elem_yr_grad = $request->elem_yr_grad;
        $new_student->highschool = $request->highschool;
        $new_student->hs_yr_grad = $request->hs_yr_grad;
        $new_student->college = $request->college;
        $new_student->college_year_ended = $request->college_year_ended;
        $new_student->is_transferee = $isTransferee;
        $new_student->is_irregular = $isIrregular;
        $new_student->save();

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function destroy($student_id)
    {
        $student = Student::find($student_id);
        if ($student) {
            $student->delete();
            return redirect()->back()->with('success', 'Program deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Program not found!');
        }
    }
}