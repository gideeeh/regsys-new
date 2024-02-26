@extends('admin.functions')
@section('content')
<div x-data="{createSection:false}">
    <x-alert-message />
    <h3 class="flex w-full justify-center bg-sky-950 px-4 rounded-md text-white mb-6 border-b-4 border-amber-300">Sections and Schedules</h3>
    <div>
        <button @click="window.location.href='{{ route('section.create') }}'" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Section</button>
        <button @click="createSection=true" class="bg-green-500 text-white text-xs px-2 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Create Section</button>
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
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div>
            <label for="term" class="block text-md font-semibold text-gray-700 mr-2">Program:</label>
            <select id="programs" name="programs" class="text-md border-gray-300 rounded-md shadow-sm" required>
                <option value="all">Select All</option>
                @foreach($programs as $program)
                <option value="{{$program->program_id}}">{{$program->program_code}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <button class="bg-rose-500 text-white text-xs px-1 py-1 rounded hover:bg-rose-600 transition ease-in-out duration-150">1st Year</button>
        <button class="bg-amber-500 text-white text-xs px-1 py-1 rounded hover:bg-amber-600 transition ease-in-out duration-150">2nd Year</button>
        <button class="bg-emerald-500 text-white text-xs px-1 py-1 rounded hover:bg-emerald-600 transition ease-in-out duration-150">3rd Year</button>
        <button class="bg-blue-500 text-white text-xs px-1 py-1 rounded hover:bg-blue-600 transition ease-in-out duration-150">4th Year</button>
    </div>
    <div>
        <button>Section 1</button>
        <button>Section 2</button>
        <button>Add Section</button>
    </div>

    <!-- Section creation -->
    <h1>Year level:</h1>
    <div class="w-full rounded-lg mb-4">
        <table class="border-solid table-auto w-full whitespace-no-wrap bg-white table-striped relative overflow-hidden">
            <thead>
                <tr class="bg-gradient-to-r from-sky-600 to-sky-800 text-white">
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Category</th>
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Code</th>
                    <th rowspan="2" class="w-2/12 bg-sky-600 text-white p-2 border border-white border-r-0">Course Description</th> 
                    <th colspan="2" class="w-2/12 bg-sky-600 text-white p-2 border border-white border-r-0">Schedule(F2F)</th>
                    <th colspan="2" class="w-2/12 bg-sky-600 text-white p-2 border border-white border-r-0">Schedule(Online)</th>
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Lecturer</th>    
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Room</th>    
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Stu Count</th>    
                    <th rowspan="2" class="w-1/12 bg-sky-600 text-white p-2 border border-white border-r-0">Action</th>
                </tr>
                <tr class="bg-gradient-to-r from-emerald-400 to-emerald-800 text-white">
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Day</th>
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Time</th>    
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Day</th>
                    <th rowspan="2" class="w-1/12 bg-emerald-400 text-white p-2 border border-white border-r-0">Time</th>    
                    </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td class="border border-gray-300">&nbsp;</td>
                    <td>
                        <div class="flex justify-start">
                            <button class="bg-green-500 w-full text-white text-xs px-1 py-1 rounded hover:bg-green-600 transition ease-in-out duration-150">Manage</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex px-8 py-2 bg-gray-300 text-sm">
            <span class="w-4/12 total-units-lec"><strong>Total Units(Lec):</strong> </span>
            <span class="w-4/12 total-units-lab"><strong>Total Units(Lab):</strong></span>
            <span class="w-4/12 total-units"><strong>Total Units:</strong> </span>
        </div>
    </div>








    <!-- Create Section Modal -->
    <div x-cloak x-show="createSection" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
            <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
                <h3 class="text-lg font-bold mb-4">Add New Section</h3>
                
                <form action="{{ route('program-lists-new-program') }}" method="POST" class="space-y-4">
                    @csrf
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
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div>
                        <label for="program_name" class="block text-sm font-medium text-gray-700">Section Name:</label>
                        <input type="text" id="program_name" name="program_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="program_description" class="block text-sm font-medium text-gray-700">Program Description:</label>
                        <textarea id="program_description" name="program_description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="degree_type" class="block text-sm font-medium text-gray-700">Degree Type:</label>
                        <select id="degree_type" name="degree_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Bachelor">Bachelor</option>
                            <option value="Associate">Associate</option>
                            <option value="Graduate">Graduate</option>
                        </select>
                    </div>
                    <div>
                        <label for="program_coordinator" class="block text-sm font-medium text-gray-700">Program Coordinator:</label>
                        <input type="text" id="program_coordinator" name="program_coordinator" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="createSection = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    
        <!-- Schedule day time -->
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