@extends('admin.functions')
@section('content')
<div x-data="{createSection:false, filterByModal:false, manageSchedule:false}" @keydown.escape.window="createSection=false; filterByModal=false; manageSchedule=false">
    <x-alert-message />
    <h3 class="flex w-full justify-center bg-sky-950 px-4 rounded-md text-white mb-6 border-b-4 border-amber-300">Sections and Schedules</h3>
    <!-- Section Metadata -->
    <!-- Info Based from Initial Data or from filterby data -->
    <div class="flex w-full justify-between mb-12">
        <div class="flex gap-36">
            <div class="">
                <h3>Acad Year: <span class="font-medium">{{$activeAcadYear->acad_year}}</span></h3>
                <h3>Program:</h3>
            </div>
            <div class="">
                <h3>Term: <span class="font-medium">{{$activeTerm}}</span></h3>
                <h3>Year Level:</h3>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <!-- <button @click="window.location.href='{{ route('section.create') }}'" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Section</button> -->
            <div>
                <button @click="createSection=true" class="bg-green-500 text-white text-md px-2 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Create Section</button>
                <button @click="filterByModal=true" class="bg-sky-500 text-white text-md px-2 py-2 rounded hover:bg-sky-600 transition ease-in-out duration-150">Filter By</button> 
            </div>
        </div>
    </div>
    <h3>Section:</h3>
    <!-- Section creation -->
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
                            <button @click="manageSchedule=true" class="bg-green-500 w-full text-white text-xs px-1 py-1 rounded hover:bg-green-600 transition ease-in-out duration-150">Manage</button>
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

    <!-- Filter By Modal -->
    <div x-cloak x-show="filterByModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full min-h-[85vh] max-h-[90vh]">
            <h2 class="mb-4">Filter By</h2>
            <div class="mb-4 mt-6">
                <label for="acad_year" class="w-full block text-md font-semibold text-gray-700">Academic Year:</label>
                <select id="acad_year" name="acad_year" class="w-full text-md border-gray-300 rounded-md shadow-sm" required>
                    @foreach($acad_years as $year)
                    <option value="{{ $year->acad_year }}" {{ (isset($activeAcadYear) && $activeAcadYear->id == $year->id) ? 'selected' : '' }}>
                        {{ $year->acad_year }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="term" class="w-full block text-md font-semibold text-gray-700">Term:</label>
                <select id="term" name="term" class="text-md w-full border-gray-300 rounded-md shadow-sm" placeholder="Select Term" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="program" class="w-full block text-md font-semibold text-gray-700">Program:</label>
                <select id="program" name="program" class="text-md w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="all">Select All</option>
                    @foreach($programs as $program)
                    <option value="{{$program->program_id}}">{{$program->program_code}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="year_level" class="block text-md font-semibold text-gray-700 mr-2">Year Level:</label>
                <select id="year_level" name="year_level" class="text-md w-full border-gray-300 rounded-md shadow-sm" placeholder="Select Year Level" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="section" class="block text-md font-semibold text-gray-700 mr-2">Section:</label>
                <select id="section" name="section" class="text-md w-full border-gray-300 rounded-md shadow-sm" placeholder="Select Year Level" required>
                    @foreach($uniqueSections as $section)
                    <option value="{{$section}}">{{$section->section_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-4 pt-2">
                <button type="button" @click="filterByModal = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                <button type="button" @click="filterByModal = false" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Create Section Modal -->
    <div x-cloak x-show="createSection" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full min-h-[85vh] max-h-[90vh]">
            <h2>Create Section</h2>
            
            <form action="{{ route('section.create') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="section_name" class="block text-md font-semibold text-gray-700 mr-2">Section Name:</label>
                    <input type="text" id="section_name" name="section_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Section [Number]">
                </div>
                <div class="flex flex-col mb-2 ">
                    <label for="academic_year" class="block text-md font-semibold text-gray-700 mr-2">Academic Year:</label>
                    <select id="academic_year" name="academic_year" class="text-md border-gray-300 rounded-md shadow-sm" required>
                        @foreach($acad_years as $year)
                        <option value="{{ $year->acad_year }}" {{ (isset($activeAcadYear) && $activeAcadYear->id == $year->id) ? 'selected' : '' }}>
                            {{ $year->acad_year }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col mb-12">
                    <label for="term" class="block text-md font-semibold text-gray-700 mr-2">Term:</label>
                    <select id="term" name="term" class="text-md w-full border-gray-300 rounded-md shadow-sm" placeholder="Select Term" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="flex flex-col mb-4">
                    <label for="year_level" class="block text-md font-semibold text-gray-700 mr-2">Year Level:</label>
                    <select id="year_level" name="year_level" class="text-md w-full border-gray-300 rounded-md shadow-sm" placeholder="Select Year Level" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" @click="createSection = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Create Section</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Manage Schedule Modal -->
    <div x-cloak x-show="manageSchedule" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto min-wd-lg max-w-xl w-full min-h-[85vh] max-h-[90vh]">
            <h2>Manage Section</h2>
            <!-- F2F Class Schedule -->
            <form action="#" method="POST">
                <div class="cursor-default w-full acad-year-card border-solid border-2 border-slate-400 rounded-md px-4 py-4 mb-6 mr-12 hover:border-sky-950">
                    <label class="block text-md font-semibold mb-2">F2F Class Schedule</label>
                    <fieldset class="mb-4"> 
                        <legend class="text-base font-medium text-gray-900 mb-2">Day(s)</legend>
                        <div class="flex justify-content items-center">
                            <!-- Mon to Wed -->
                            <div class="w-1/2 pl-4">
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Monday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Monday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Tuesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Tuesday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Wednesday</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Thu to Sat -->
                            <div class="w-1/2 pl-4">
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Thursday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Friday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="f2f_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Saturday</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label for="f2f_start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                            <input type="time" id="f2f_start_time" name="f2f_start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="w-1/2">
                            <label for="f2f_end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                            <input type="time" id="f2f_end_time" name="f2f_end_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                </div>
                <!-- Online Class Schedule -->
                <div class="cursor-default w-full acad-year-card border-solid border-2 border-slate-400 rounded-md px-4 py-4 mb-6 mr-12 hover:border-sky-950">
                    <label class="block text-md font-semibold mb-2">F2F Class Schedule</label>
                    <fieldset class="mb-4"> 
                        <legend class="text-base font-medium text-gray-900 mb-2">Day(s)</legend>
                        <div class="flex justify-content items-center">
                            <!-- Mon to Wed -->
                            <div class="w-1/2 pl-4">
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Monday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Monday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Tuesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Tuesday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Wednesday</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Thu to Sat -->
                            <div class="w-1/2 pl-4">
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Thursday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Friday</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center space-x-3">
                                        <input type="checkbox" name="online_days[]" value="Wednesday" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        <span class="text-gray-700">Saturday</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time:</label>
                            <input type="time" id="online_start_time" name="online_start_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="w-1/2">
                            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time:</label>
                            <input type="time" id="online_end_time" name="online_end_time" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="prof" class="block text-sm font-medium text-gray-700">Lecturer:</label>
                    <input type="text" id="prof" name="prof" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="room" class="block text-sm font-medium text-gray-700">Room:</label>
                    <input type="text" id="room" name="room" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="class_limit" class="block text-sm font-medium text-gray-700">Class Limit:</label>
                    <input type="number" id="class_limit" name="class_limit" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end space-x-4 pt-6">
                    <button type="button" @click="manageSchedule = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Confirm</button>
                </div>
            </form>
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