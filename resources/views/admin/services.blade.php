@extends('admin.appointments')
@section('content')
<div x-data='{addService:false, updateService:false, deleteService:false, serviceId: null, selectedServiceName: "", selectedDescription: ""}' @keydown.escape ="addService=false; updateService=false; deleteService=false">
    <x-alert-message />
    <h3 class="flex w-full justify-center bg-sky-950 px-4 rounded-md text-white mb-6 border-b-4 border-amber-300">Registrar Appointments Services</h3>
    <button @click="addService = true" id="addService" class="mb-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">+ Add Service</button>

    <!-- Services Table -->
    <div class="py-4">
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
            <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                <thead>
                    <tr class="text-left">
                        <th class="bg-blue-500 text-white p-2">Service Name</th>
                        <th class="bg-blue-500 text-white p-2">Service Description</th>
                        <th class="bg-blue-500 text-white p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                    <tr class="border-b hover:bg-gray-100 cursor-pointer">
                        <td class="border-dashed border-t border-gray-300 p-2">{{$service->service_name}}</td>
                        <td class="border-dashed border-t border-gray-300 p-2">{{$service->description}}</td>
                        <td class="border-dashed border-t border-gray-300 p-2">
                            <div class="flex space-x-4">
                                <button 
                                    @click.stop="updateService = true; 
                                        serviceId = {{$service->id}}; 
                                        selectedServiceName='{{$service->service_name}}'; 
                                        selectedDescription='{{$service->description}}';" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
                                <button @click.stop="deleteService = true; serviceId = {{$service->id}}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
    
    <!-- Add Service Modal -->
    <div x-cloak x-show="addService" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
            <h3 class="text-lg font-bold mb-4">Add Service</h3>
            
            <form action="{{route('appointments.create')}}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="service_name" class="block text-sm font-medium text-gray-700">Service Name:</label>
                    <input type="text" id="service_name" name="service_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="service_description" class="block text-sm font-medium text-gray-700">Service Description:</label>
                    <textarea name="description" id="description" x-model="textarea" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="addService = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Save Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Modal -->
    <div x-cloak x-show="updateService" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
        <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full max-h-[80vh]">
            <h3 class="text-lg font-bold mb-4">Update Service</h3>  
            <form :action="'/admin/appointments/services/update/' + serviceId" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label for="update_service_name" class="block text-sm font-medium text-gray-700">Service Name:</label>
                    <input type="text" id="update_service_name" name="service_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" x-model="selectedServiceName">
                </div>
                <div>
                    <label for="update_description" class="block text-sm font-medium text-gray-700">Service Description:</label>
                    <textarea name="update_description" id="update_description" x-model="selectedDescription" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" @click="updateService=false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition ease-in-out duration-150">Save Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-cloak x-show="deleteService" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 z-50">
            <div class="modal-content bg-white p-8 rounded-lg shadow-lg overflow-auto max-w-md w-full">
                <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
                <p>Are you sure you want to delete this service?</p>
                <div class="flex justify-end mt-4">
                <div class="flex items-center">
                    <button @click="deleteService = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition ease-in-out duration-150 mr-2">Cancel</button>
                    <form :action="'/admin/appointments/services/delete/' + serviceId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition ease-in-out duration-150">Delete</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
</div>
@endsection