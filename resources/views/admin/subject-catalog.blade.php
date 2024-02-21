@extends('admin.functions')
@section('content')
    <div x-data="{ showModal: @json($errors->any()), showErrorModal: @json($errors->any()), searchPreReq1: '', filteredSubjects: [], searchTerm: '{{ $searchTerm ?? '' }}'}" @keydown.escape.window="showModal = false">
        <a href="{{ route('subject-catalog') }}" class="font-semibold text-xl text-gray-800 leading-tight no-underline hover:underline">
            <span class="text-2xl font-semibold mb-4">Subjects Catalog</span>
        </a>
        <div class="flex justify-between space-x-4 mt-6">
            <button @click="showModal = true" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">+ Add Subject</button>
            <x-search-form action="{{ route('subject-catalog') }}" placeholder="Search Subject" />
        </div>
        <!-- Add Subject Modal --> 
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
            <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
                <h3 class="text-lg font-bold mb-4">Add New Program</h3>
                <form action="{{ route('subject-catalog-new-subject') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="subject_code" class="block text-sm font-medium text-gray-700">Subject Code:</label>
                        <input type="text" id="subject_code" name="subject_code" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="subject_name" class="block text-sm font-medium text-gray-700">Subject Name:</label>
                        <input type="text" id="subject_name" name="subject_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="subject_description" class="block text-sm font-medium text-gray-700">Subject Description:</label>
                        <textarea id="subject_description" name="subject_description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="units_lec" class="block text-sm font-medium text-gray-700">Units Lecture:</label>
                        <input type="number" id="units_lec" name="units_lec" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="units_lab" class="block text-sm font-medium text-gray-700">Units Lab:</label>
                        <input type="number" id="units_lab" name="units_lab" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="prereq1" class="block text-sm font-medium text-gray-700">Pre-Requisite 1:</label>
                        <select id="prereq1" name="prereq1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select Pre-Requisite1</option>
                            @foreach ($all_subjects as $subject)
                                <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="prereq2" class="block text-sm font-medium text-gray-700">Pre-Requisite 2:</label>
                        <select id="prereq2" name="prereq2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select Pre-Requisite1</option>
                            @foreach ($all_subjects as $subject)
                                <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Create Subject</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Subjects Table -->
        <div class="py-4">
            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                        <tr class="text-left">
                            <th class="bg-blue-500 text-white p-2">Subject Code</th>
                            <th class="bg-blue-500 text-white p-2">Subject Name</th>
                            <th class="bg-blue-500 text-white p-2">Units (Lec)</th>
                            <th class="bg-blue-500 text-white p-2">Units (Lab)</th>
                            <th class="bg-blue-500 text-white p-2">Total Units</th>
                            <th class="bg-blue-500 text-white p-2">Pre-Req 1</th>
                            <th class="bg-blue-500 text-white p-2">Pre-Req 2</th>
                            <th class="bg-blue-500 text-white p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                        <tr class="cursor-pointer">
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->subject_code }}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->subject_name }}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->units_lec }}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->units_lab }}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->units_lec + $subject->units_lab }}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->prereq1 ?? '-'}}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">{{ $subject->prereq2 ?? '-'}}</td>
                            <td class="border-dashed border-t border-gray-200 p-2">
                                <div class="flex justify-end space-x-4">
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $subjects->links() }}
            </div>
        </div>
        <!-- Error Modal -->
        <div x-show="showErrorModal" x-init="setTimeout(() => showErrorModal = false, 3000)" class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center px-4 py-6 z-50" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white p-4 rounded-lg shadow-lg max-w-sm w-full">
                <h4 class="font-bold text-lg mb-2">Error</h4>
                <p class="text-sm text-red-600">Whoops! Something went wrong.</p>
                <ul class="list-disc list-inside text-sm text-red-600 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection