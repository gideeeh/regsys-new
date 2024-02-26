@extends('admin.enrollments')
@section('content')
<div x-data="{ selectedStudent:'', selectedSubjects: [], enrollSubject: false}" class="pb-12">
    <x-alert-message />
    <h3 class="flex w-full justify-center bg-sky-950 px-4 rounded-md text-white mb-6">Student Enrollment</h3>
    <!-- <div> -->
    <form id="enrollmentForm" method="POST" action="{{ route('enrollments.store') }}">
    @csrf
    <input type="hidden" name="selectedSubjects" id="selectedSubjectsInput" value="">
    <div class="flex justify-between items-start mb-12">
        <div>
            <div class="flex items-center mb-2">
                <label for="acad_year" class="block text-md font-semibold text-gray-700 mr-2">Academic Year:</label>
                <select id="acad_year" name="acad_year" class="text-md border-gray-300 rounded-md shadow-sm" required>
                    @foreach($acad_years as $year)
                    <option value="{{ $year->acad_year }}" {{ (isset($activeAcadYear) && $activeAcadYear->id == $year->id) ? 'selected' : '' }}>
                        {{ $year->acad_year }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center">
                <label for="term" class="block text-md font-semibold text-gray-700 mr-2">Term:</label>
                <select id="term" name="term" class="text-md w-1/4 border-gray-300 rounded-md shadow-sm" placeholder="Select Term" required>
                    <option value="{{$activeTerm}}" hidden>{{$activeTerm}}</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="w-5/12">
        @if(isset($students))
            <select id="stu_to_enroll" name="stu_to_enroll" x-model="stu_to_enroll" style="width: 100%;"></select>
        @endif
        </div>
    </div>
    <!-- </div> -->
    <div class="flex justify-between mb-6"><!-- Form insert -->
        <input type="hidden" name="student_id" id="student_id" value="">
        <div class="w-4/12">
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
            <input type="text" id="first_name" name="first_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="w-3/12">
            <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="w-3/12">
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required> 
        </div>
        <div class="w-1/12">
            <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix:</label>
            <select name="suffix" id="suffix" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="" disabled selected hidden></option>
                <option value="Jr.">Jr.</option>
                <option value="Sr.">Sr.</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
            </select>
        </div>
    </div>
    <!-- Enrollment -->
    <div class="flex justify-between items-center mb-12">
        <input type="hidden" name="status" id="status" value="success">
        <div class="mr-4 w-3/12">
            <label for="student_number" class="block text-sm font-medium text-gray-700 mr-2">Student Number:</label>
            <input type="text" id="student_number" name="student_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="flex flex-col items-start mr-4 w-2/12">
            <label for="program_id" class="block text-sm font-medium text-gray-700 mr-2">Course:</label>
            <select id="program_id" name="program_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected hidden></option>
                @foreach($programs as $program)
                <option value="{{ $program->program_id }}">{{ $program->program_code }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col items-start mr-4">
            <label for="year_level" class="block text-sm font-medium text-gray-700 mr-2">Year:</label>
            <select name="year_level" id="year_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected hidden></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="flex flex-col items-start mr-4 w-2/12">
            <label for="enrollment_method" class="block text-sm font-medium text-gray-700 mr-2">Enrollment Method:</label>
            <select name="enrollment_method" id="enrollment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected hidden></option>
                <option value="continuing">Continuing</option>
                <option value="new">New</option>
                <option value="shiftee">Shiftee</option>
                <option value="transferee">Transferee</option>
            </select>
        </div>
        <div class="flex flex-col items-start w-3/12">
            <label for="scholarship_type" class="block text-sm font-medium text-gray-700 mr-2">Scholarship Type:</label>
            <select name="scholarship_type" id="scholarship_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected hidden></option>
                <option value="none">None</option>
                <option value="working">Working</option>
                <option value="academic">Academic</option>
            </select>
        </div>
    </div>
    <!-- Subjects To Enroll-->
    <div class="w-full rounded-lg mb-4">
        <table class="border-solid table-auto w-full whitespace-no-wrap bg-white table-striped relative overflow-hidden">
            <thead>
                <tr class="bg-gradient-to-r from-sky-600 to-sky-800 text-white">
                    <th rowspan="2" class="w-2/12 bg-sky-600 text-white p-2 border border-white border-r-0">Course Code</th>
                    <th rowspan="2" class="w-3/12 bg-sky-600 text-white p-2 border border-white border-r-0">Course Description</th>
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Section</th>
                    <th colspan="3" class="w-3/12 bg-sky-600 text-white p-2 border border-white border-r-0">Units</th> 
                    <th colspan="2" class="w-2/12 bg-sky-600 text-white p-2 border border-white border-r-0">Schedule</th>
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Action</th>
                </tr>
                <tr class="bg-gradient-to-r from-emerald-400 to-emerald-800 text-white">
                    <th class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Lec</th>
                    <th class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Lab</th>
                    <th class="w-1/12 bg-emerald-800 text-white p-2 border border-white border-r-0">Total</th>
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Day</th>
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Time</th>    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <div>
                            <select id="enroll_subjects" name="enroll_subjects" x-model="selectedSubjects" style="width: 100%;">
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                    </td>
                    <td class="border border-gray-300"><!-- Insert Units Lec --></td>
                    <td class="border border-gray-300"><!-- Insert Units Lab --></td>
                    <td class="border border-gray-300"><!-- Units Lec + Lab --></td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <div class="flex px-8 py-2 bg-gray-300 text-sm">
            <span class="w-4/12 total-units-lec"><strong>Total Units(Lec):</strong> </span>
            <span class="w-4/12 total-units-lab"><strong>Total Units(Lab):</strong></span>
            <span class="w-4/12 total-units"><strong>Total Units:</strong> </span>
        </div>
    </div>
    <div class="flex justify-end">
        <button type="button" class="enroll shadow bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition ease-in-out duration-150">Enroll</button>
        <!-- <button type="button" class="log-data shadow bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition ease-in-out duration-150">Log</button> -->
    </div>
    </form>

</div>

<script>
    $(document).ready(function() {
        let $enrollSubjects = $('#enroll_subjects');
        let selectedSubjects = [];

        function logCurrentInputs() {
            var inputs = $('#enrollmentForm').find('input, select').serializeArray();
            console.log("Current Form Inputs:", inputs);
            console.log("Selected Subjects:", selectedSubjects);
        }

        $('.log-data').on('click', function() {
            updateSelectedSubjects();
            logCurrentInputs();
        });
        $('#enroll_subjects').select2({
            width: 'resolve',
            placeholder: "Select Subject to Enroll",
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '/admin/functions/get-subjects',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.subject_id,
                                text: item.subject_code + ' - ' + item.subject_name,
                                subject_code: item.subject_code,
                                subject_name: item.subject_name,
                                units_lec: item.units_lec,
                                units_lab: item.units_lab,
                                total_units: item.units_lec + item.units_lab
                            };
                        })
                    };
                },
                cache: true
            }
        }).on("select2:select", function(e) {
            let subjectDetails = e.params.data;
            selectedSubjects.push({
                subject_id: subjectDetails.id,
            });

            let newRow = `<tr data-subject-id="${subjectDetails.id}">
                <td class="border border-gray-300 text-center""><strong>${subjectDetails.subject_code}</strong></td>
                <td class="border border-gray-300 text-center"">${subjectDetails.subject_name}</td>
                <td class="border border-gray-300 text-center""><!-- Section --></td>
                <td class="border border-gray-300 text-center">${subjectDetails.units_lec}</td>
                <td class="border border-gray-300 text-center"">${subjectDetails.units_lab}</td>
                <td class="border border-gray-300 text-center"">${subjectDetails.total_units}</td>
                <td class="border border-gray-300 text-center""><!-- Day --></td>
                <td class="border border-gray-300 text-center""><!-- Time --></td>
                <td class="border border-gray-300">
                    <button type="button" class="remove-subject bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition ease-in-out duration-150">Remove</button>
                </td>
            </tr>`;

            $("table tbody").prepend(newRow);

            $enrollSubjects.val(null).trigger('change');
            updateTotalUnits();
        });

        $('table').on('click', '.remove-subject', function() {
            let subjectIdToRemove = $(this).closest('tr').data('subjectId');
            $(this).closest('tr').remove();
            selectedSubjects = selectedSubjects.filter(subject => subject.subject_id != subjectIdToRemove);

            updateTotalUnits();
            updateSelectedSubjects();
        });

        $('.enroll').on('click', function() {
            updateSelectedSubjects();
            var form = document.getElementById('enrollmentForm');
            if (form.checkValidity()) {
                if (selectedSubjects.length > 0) {
                    form.submit();
                } else {
                    alert("Please select at least one subject to enroll.");
                }
            } else {
                form.reportValidity();
            }
        });

        function updateTotalUnits() {
            let totalLec = 0;
            let totalLab = 0;

            $("table tbody tr").each(function() {
                let lecUnits = parseInt($(this).find("td:nth-child(4)").text()) || 0;
                let labUnits = parseInt($(this).find("td:nth-child(5)").text()) || 0;

                totalLec += lecUnits;
                totalLab += labUnits;
            });

            let totalUnits = totalLec + totalLab;

            $(".total-units-lec").text(`Total Units(Lec): ${totalLec}`);
            $(".total-units-lab").text(`Total Units(Lab): ${totalLab}`);
            $(".total-units").text(`Total Units: ${totalUnits}`);
        }

        function updateSelectedSubjects() {
            $("table tbody tr").each(function() {
                // Assuming the subject ID is stored in the row's data attribute
                let subjectId = $(this).data('subjectId');
                if (subjectId) {
                    selectedSubjects.push(subjectId);
                }
            });
            $('#selectedSubjectsInput').val(JSON.stringify(selectedSubjects.map(subject => subject.subject_id)));
        }

        $('#stu_to_enroll').select2({
            width: 'resolve',
            placeholder: "Student Name or Student Number",
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '/admin/students/get-students',
                dataType: 'json',
                delay: 20,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.student_id,
                                text: item.student_number + ' - ' + item.first_name + ' ' + item.last_name
                            };
                        })
                    };
                },
                cache: true
            }
        }).on("select2:select", function(e) {
        var studentId = e.params.data.id;
            $.ajax({
                url: `/admin/students/get-students/${studentId}`,
                type: "GET",
                success: function(response) {
                    $('#student_id').val(response.student_id);
                    $('#first_name').val(response.first_name);
                    $('#middle_name').val(response.middle_name);
                    $('#last_name').val(response.last_name);
                    $('#suffix').val(response.suffix);
                    $('#student_number').val(response.student_number);
                    $('#studentName').text(response.first_name);
                    $('#studentEmail').text(response.personal_email);
                    $('#studentMobile').text(response.phone_number);
                    // $('#studentDetails').show();
                },
                error: function(error) {
                    console.error('Error fetching student details:', error);
                }
            });
        });
        updateTotalUnits();
    });
</script>
@endsection