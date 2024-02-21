@extends('admin.enrollments')
@section('content')
<div x-data="{ selectedStudent:''}">

    <h1>Enroll Student</h1>
    <div>
        @if(isset($students))
        <select id="stu_to_enroll" name="stu_to_enroll" x-model="stu_to_enroll" style="width: 100%;"></select>
        @endif
        <span>Select Academic Year</span>
        <span>Term: </span>
    </div>
    <label for="enrollment_method">Enrollment Method:</label>
    <select name="enrollment_method" id="enrollment_method">
        <option value="" selected>Continuing</option>
        <option value="">New</option>
        <option value="">Shiftee</option>
        <option value="">Transferee</option>
    </select>
    <div id="studentDetails">
        <div>
            <p>Student Name: <span id="studentName"></span></p>
            <label for="suffix" class="block text-sm font-medium text-gray-700">Course:</label>
            <x-select-option name="program" id="program" :default="old('program', $student->program ?? '')" 
                :options="$programs->pluck('program_code', 'program_id')" class="mt-1 block w-2/12" />
            <x-input-error class="mt-2" :messages="$errors->get('suffix')" />
        </div>
        <div>
            <p>Student Number: <span></span></p>
            <p>Scholarship: <span></span></p>
        </div>
    </div>
    
    <div class="w-full">
        <table class="w-full">
            <thead>
                <tr>
                    <th rowspan="2" class="w-2/12">Course Code</th>
                    <th rowspan="2" class="w-3/12">Course Description</th>
                    <th colspan="3" class="w-3/12">Units</th> 
                    <th colspan="2" class="w-2/12">Schedule</th>
                    <th rowspan="2" class="w-1/12">Section</th>
                    <th rowspan="2" class="w-1/12">Action</th>
                </tr>
                <tr>
                    <th class="w-1/12">Lec</th>
                    <th class="w-1/12">Lab</th>
                    <th class="w-1/12">Total</th>
                    <th rowspan="2" class="w-1/12">Day</th>
                    <th rowspan="2" class="w-1/12">Time</th>    
                </tr>
            </thead>
            <tbody>
                <!-- Table body with data rows -->
            </tbody>
        </table>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 first
        $('#stu_to_enroll').select2({
            width: 'resolve',
            placeholder: "Enter Student Name | Student Number",
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
        // Make an AJAX request to your server to fetch the student details
        $.ajax({
            url: `/admin/students/get-students/${studentId}`,
            type: "GET",
            success: function(response) {
                // Assuming the response contains the email and mobile number
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

    });
</script>
@endsection