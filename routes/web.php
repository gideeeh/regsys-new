<?php

use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CourseListingsController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnrolledSubjectsController;
use App\Http\Controllers\EnrollmentsController;
use App\Http\Controllers\FacultyRecordsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramSubjectController;
use App\Http\Controllers\RegistrarFunctionsController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentNoteController;
use App\Http\Controllers\StudentRecordsController;
use App\Http\Controllers\SubjectCatalogController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserDashboardController;
use App\Livewire\ProgramAndCourseManagement;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard'); // Redirect admins to the admin dashboard
        } else {
            return redirect('/user/dashboard'); // Redirect regular users to the user dashboard
        }
    } else {
        return redirect()->route('login'); // Redirect guests to the login page
    }
});

Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/subjects', [SubjectController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

/* Admin Middleware */
Route::middleware(['auth','isAdminUser'])->group(function() {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
/* Student Records */
    Route::get('/admin/student-records', [StudentRecordsController::class, 'index'])->name('student-records');
    Route::get('/admin/student-records/{student}', [StudentRecordsController::class, 'show'])->name('student-records.show');
    Route::get('/student/student-records/add_student', function () {
        return view('admin.add-student');
    })->name('student.add');
    Route::post('/student/student-records/add_student', [StudentRecordsController::class, 'store'])->name('student.store');
    Route::post('/student/{student_id}/notes', [StudentNoteController::class, 'store'])->name('student-notes.store');
    Route::delete('/student/student-records/delete_student/{student_id}', [StudentRecordsController::class, 'destroy'])->name('student-delete');
    Route::get('/student/student-records/edit/{student}', [StudentRecordsController::class, 'edit'])->name('student.edit');
    Route::patch('/student/student-records/edit/update/{student}', [StudentRecordsController::class, 'update_personal'])->name('student.update');
/* Faculty Records */
    Route::get('/admin/faculty-records', [FacultyRecordsController::class, 'index'])->name('faculty-records');
    Route::get('/admin/faculty-records/{faculty}', [FacultyRecordsController::class, 'show'])->name('faculty-records.show');
/* Subject Profile */
    Route::get('/admin/course-listings', [CourseListingsController::class, 'index'])->name('course-listings');
    Route::get('/admin/course-listings/{course}', [CourseListingsController::class, 'show'])->name('course-listings.show');
/* Enrollments */
    Route::get('/admin/enrollment-records', [EnrollmentsController::class, 'index'])->name('enrollment-records');
    Route::get('/admin/enrollment-records/{enrollment_id}', [EnrollmentsController::class, 'show'])->name('enrollment-records.show');
    Route::get('/admin/enrollments/enroll', [EnrollmentsController::class, 'enroll'])->name('enrollments.enroll');
    Route::post('/admin/enrollments/enroll/enroll_student',[EnrollmentsController::class, 'store'])->name('enrollments.store');
/* Enroll Subjects */
    Route::post('/admin/enrollments/enroll/enroll_subjects/{enrollment_id}',[EnrolledSubjectsController::class, 'store'])->name('enroll.subjects');

/* Program Management */
    Route::get('/admin/functions/program-course-management/program_list', [ProgramController::class, 'index'])->name('program-list');
    /* Sub - Program Profile */
    Route::get('/admin/functions/program-course-management/program_list/{program_id}', [ProgramController::class, 'show'])->name('program-list.show');
    Route::get('/admin/functions/program-course-management/program_list/{program_id}/assign_subject', [SubjectController::class, 'search'])->name('program-lists-subjects.search');
    Route::post('/admin/functions/program-course-management/program_list/save-program', [ProgramController::class, 'store'])->name('program-lists-new-program');    
    Route::post('/admin/functions/program-course-management/program_list/{program_id}/save-assign_subject', [ProgramSubjectController::class, 'store'])->name('program-subject.save');
    Route::delete('/admin/functions/program-course-management/program_list/delete-program/{program_id}', [ProgramController::class, 'destroy'])->name('program-lists-delete-program');
    Route::patch('/admin/functions/program-course-management/program_list/update-program/{id}', [ProgramController::class, 'update'])->name('program-lists-update-program');
/* Subject Catalog */
    Route::get('/admin/functions/program-course-management/subject_catalog', [SubjectCatalogController::class, 'index'])->name('subject-catalog');
    Route::post('/admin/functions/program-course-management/subject_catalog/save-subject', [SubjectCatalogController::class, 'store'])->name('subject-catalog-new-subject');
    Route::delete('/admin/functions/program-course-management/subject_catalog/delete/{id}',[SubjectCatalogController::class,'delete'])->name('subject-catalog.delete');
    Route::patch('/admin/functions/program-course-management/subject_catalog/update/{id}',[SubjectCatalogController::class,'update'])->name('subject-catalog.update');
/* Academic Calendar */
    Route::get('/admin/functions/program-course-management/academic_calendar', [AcademicCalendarController::class, 'index'])->name('academic-calendar');
    Route::post('/admin/functions/program-course-management/academic_calendar/add-event', [AcademicCalendarController::class, 'store'])->name('academic-calendar-add-event');
    Route::post('admin/functions/program-course-management/academic_calendar/set-acad-year',[AcademicYearController::class, 'store'])->name('acad-year-set');
    Route::delete('/admin/functions/program-course-management/academic_calendar/delete-event/{id}', [AcademicCalendarController::class, 'destroy'])->name('academic-calendar-delete-event');
/* Academic Year */
    Route::get('/admin/functions/program-course-management/academic_year',[AcademicYearController::class, 'index'])->name('academic-year');
    Route::post('/admin/functions/program-course-management/academic_year/add_acad_year',[AcademicYearController::class, 'store'])->name('academic-year.store');
    Route::patch('/admin/functions/program-course-management/academic_year/update_acad_year/{id}',[AcademicYearController::class, 'update'])->name('academic-year.update');
    Route::delete('/admin/functions/program-course-management/academic_year/delete_acad_year/{id}',[AcademicYearController::class, 'destroy'])->name('academic-year.delete');
/* Class Schedules */
    Route::get('/admin/functions/program-course-management/sections',[SectionController::class, 'index'])->name('sections');
    // Route::get('/admin/functions/program-course-management/sections/create',[SectionController::class, 'create_section'])->name('section.create');
    Route::post('/admin/functions/program-course-management/sections/create',[SectionController::class, 'store'])->name('section.create');

/* Local APIs */
    Route::get('/admin/students/get-students/', [StudentRecordsController::class, 'student_json'])->name('students.json');
    Route::get('/admin/students/get-students/{student_id}', [StudentRecordsController::class, 'fetch_student_json'])->name('students.fetch');
    Route::get('/admin/functions/get-subjects', [SubjectController::class, 'search'])->name('gimme-subjects');
    Route::get('/admin/functions/get-subjects/{subject_id}', [SubjectCatalogController::class, 'fetch_subject'])->name('subject.fetch');
    Route::get('/admin/functions/get-programs', [ProgramController::class, 'program_json'])->name('program.json');
    Route::get('/admin/functions/get-programs/{program_id}', [ProgramController::class, 'fetch_program_json'])->name('program.json');
    // Route::get('/admin/functions/get-program-subjects/', [ProgramSubjectController::class, 'program_subjects_json'])->name('program-subjects.json');
    Route::get('/admin/functions/get-program-subjects/', [ProgramSubjectController::class, 'fetchProgramSubjects'])->name('program-subjects.json');
    Route::get('/program/{program_id}/subjects/{year}/{term}', [ProgramSubjectController::class, 'fetchSubjects'])->name('fetch.subjects');
    Route::get('/sections/fetch', [SectionController::class,'fetchSections'])->name('fetch.sections');
});

require __DIR__.'/auth.php';
