@extends('admin.functions')
@section('content')
<div x-data="{ showModal: false, showSetAcadYearTerm: false }" @close-modal.window="{showModal = false, showSetAcadYearTerm = false }">
    <a href="{{ route('academic-calendar') }}">
        <h2 class="text-2xl font-semibold mb-4">Academic Calendar</h2>
    </a>
    <button @click="showModal = true"class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">+ Add Event</button>    
    <button @click="showSetAcadYearTerm = true"class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition ease-in-out duration-150">Set Acad Year</button>    
    <div id='calendar' class="py-4"></div>
    <div x-cloak x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
            <h3 class="text-lg font-bold mb-4">Add New Event</h3>
            <form id="addEventForm" class="space-y-4">
                <input type="text" id="eventTitle" placeholder="Event Title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <input type="datetime-local" id="startTime" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <input type="datetime-local" id="endTime" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <textarea id="eventComments" placeholder="Comments" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Close</button>
                    <button type="submit" @click="showModal = false" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Add Event</button>
                </div>
            </form>
        </div>
    </div>
    <div x-cloak x-show="showSetAcadYearTerm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
            <h3 class="text-lg font-bold mb-4">Set School Year and Term</h3>
            <form action="{{ route('acad-year-set') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="acad_year" class="block text-sm font-medium text-gray-700">Academic Year:</label>
                    <select id="acad_year" name="acad_year" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @php
                    $currentYear = date('Y');
                    $endYear = $currentYear + 10;
                    for ($year = $currentYear; $year < $endYear; $year++) {
                        $acad_year = $year . '-' . ($year + 1);
                        echo "<option value='{$acad_year}'>{$acad_year}</option>";
                    }
                    @endphp
                    </select>                
                </div>
                <div>
                    <label for="acad_year_start" class="block text-sm font-medium text-gray-700">Acad Year (Start):</label>
                    <input type="date" name="acad_year_start" id="acad_year_start" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="acad_year_end" class="block text-sm font-medium text-gray-700">Acad Year (End):</label>
                    <input type="date" name="acad_year_end" id="acad_year_end" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_1_start" class="block text-sm font-medium text-gray-700">Term 1 (Start):</label>
                    <input type="date" name="term_1_start" id="term_1_start" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_1_end" class="block text-sm font-medium text-gray-700">Term 1 (End):</label>
                    <input type="date" name="term_1_end" id="term_1_end" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_2_start" class="block text-sm font-medium text-gray-700">Term 2 (Start):</label>
                    <input type="date" name="term_2_start" id="term_2_start" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_2_end" class="block text-sm font-medium text-gray-700">Term 2 (End):</label>
                    <input type="date" name="term_2_end" id="term_2_end" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_3_start" class="block text-sm font-medium text-gray-700">Term 3 (Start):</label>
                    <input type="date" name="term_3_start" id="term_3_start" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div>
                    <label for="term_3_end" class="block text-sm font-medium text-gray-700">Term 3 (End):</label>
                    <input type="date" name="term_3_end" id="term_3_end" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">           
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="showSetAcadYearTerm = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Close</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Set Acad Year & Term</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let calendar; 

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: JSON.parse('@json($events)'),
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true, 
            meridiem: 'short' 
        },
        eventDidMount: function(info) {
            const dateTimeOptions = {
                month: 'short', 
                day: '2-digit',
                year: 'numeric', 
                hour: '2-digit',
                minute: '2-digit',
                hour12: true, 
                timeZone: 'Asia/Manila' 
            };
            const startDate = info.event.start ? new Date(info.event.start) : null;
            const endDate = info.event.end ? new Date(info.event.end) : null;
            
            const startString = startDate ? `${startDate.toLocaleTimeString('en-PH', dateTimeOptions)}` : 'No start date';
            const endString = endDate ? `${endDate.toLocaleTimeString('en-PH', dateTimeOptions)}` : 'No end date';

            tippy(info.el, {
                content: `Title: ${info.event.title}<br>
                        Start: ${startString}<br>
                        End: ${endString}<br>
                        Comments: ${info.event.extendedProps.comments}`,
                allowHTML: true,
            });
        },
        eventClick: function(info) {
            let eventDetail = {
                title: info.event.title,
                start: info.event.start.toISOString(),
                end: info.event.end.toISOString(),
                comments: info.event.extendedProps.comments
            };
            document.dispatchEvent(new CustomEvent('open-event-modal', { detail: eventDetail }));
        }
    });
    calendar.render();

    document.getElementById('addEventForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var title = document.getElementById('eventTitle').value;
        var start = document.getElementById('startTime').value;
        var end = document.getElementById('endTime').value;
        var comments = document.getElementById('eventComments').value;

        axios.post('/admin/functions/program-course-management/academic_calendar/add-event', {
            title: title,
            start: start,
            end: end,
            comments: comments,
        })
        .then(function (response) {
            calendar.addEvent({
                title: title,
                start: start,
                end: end,
                comments: comments,
            });
            showModal = false; 

            document.getElementById('addEventForm').reset();
        })
        .catch(function (error) {
            console.log(error);
        });
    });
});
</script>