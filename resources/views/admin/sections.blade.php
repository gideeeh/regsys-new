@extends('admin.functions')
@section('content')
<div>
    <x-alert-message />
    <h3 class="flex w-full justify-center bg-sky-950 px-4 rounded-md text-white mb-6 border-b-4 border-amber-300">Sections and Schedules</h3>
    <h2>Subject: </h2>
    <select id="subjects" name="subjects" x-model="subjects" style="width: 50%;"></select>
    <div x-show="manageSections">
        <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Create Section</button>
    </div>
        <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Add Schedule</button>
    <div x-show="createSectionModal">
        <h1 id="subject_name"></h1>
        <div class="w-4/12">
            <label for="section_name" class="block text-sm font-medium text-gray-700">Section name:</label>
            <input type="text" id="section_name" name="section_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="w-full">
            <label class="block text-sm font-medium text-gray-700 mb-2">Class Schedule:</label>
            <p>Schedule 1</p>
            <fieldset class="mb-4">
                <legend class="text-base font-medium text-gray-900">Day(s)</legend>
                <div class="mt-2 space-y-2">
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Monday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Monday</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Tuesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Tuesday</span>
                        </label>
                    </div>
                    <!-- Repeat for other days as needed -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Wednesday</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Thursday</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Friday</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-gray-700">Saturday</span>
                        </label>
                    </div>
                    <!-- Include all days you need -->
                </div>
            </fieldset>
            
            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="w-1/2">
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#subjects').select2({
            width: 'resolve',
            placeholder: "Search Subject",
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '/admin/functions/get-subjects',
                dataType: 'json',
                delay: 20,
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.subject_id,
                                text: item.subject_code + ' - ' + item.subject_name
                            };
                        })
                    };
                },
                cache: true
            }
        }).on("select2:select", function(e) {
        var subjectId = e.params.data.id;
            $.ajax({
                url: `/admin/functions/get-subjects/${subjectId}`,/* /admin/functions/get-subjects/{subject_id} */
                type: "GET",
                success: function(response) {
                    $('#student_id').val(response.student_id);
                    $('#first_name').val(response.first_name);
                    $('#middle_name').val(response.middle_name);
                    $('#last_name').val(response.last_name);
                    $('#suffix').val(response.suffix);
                    $('#student_number').val(response.student_number);
                    $('#subject_name').text(response.subject_code + ' - ' + response.subject_name);
                    $('#studentEmail').text(response.personal_email);
                    $('#studentMobile').text(response.phone_number);
                },
                error: function(error) {
                    console.error('Error fetching student details:', error);
                }
            });
        });

    });
</script>
@endsection