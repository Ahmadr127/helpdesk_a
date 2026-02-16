@extends('admin.layouts.app')
@section('title', 'Master Data')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Dashboard Header with Card/Border -->
    <div class="bg-gradient-to-r from-white to-gray-50 rounded-lg shadow-md p-6 mb-8 border-l-4 border-blue-500">
        <h1 class="text-2xl font-semibold text-gray-800">Master Data</h1>
        <p class="text-gray-600 mt-2">Overview of all master data categories in the system</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Categories Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Categories</h2>
                <a href="{{ route('admin.master.categories.index') }}"
                    class="text-blue-600 hover:text-blue-800 transition-colors">Manage
                    →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="categoriesTable">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all">
                            <td class="px-6 py-4">{{ $category->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $category->status ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $category->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Departments</h2>
                <a href="{{ route('admin.master.departments.index') }}"
                    class="text-blue-600 hover:text-blue-800 transition-colors">Manage
                    →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="departmentsTable">
                        @foreach($departments as $department)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all">
                            <td class="px-6 py-4">{{ $department->name }}</td>
                            <td class="px-6 py-4">{{ $department->code }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $department->status ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                                    {{ $department->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $department->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Buildings Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Buildings</h2>
                <a href="{{ route('admin.master.buildings.index') }}"
                    class="text-blue-600 hover:text-blue-800 transition-colors">Manage
                    →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="buildingsTable">
                        @foreach($buildings as $building)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all">
                            <td class="px-6 py-4">{{ $building->name }}</td>
                            <td class="px-6 py-4">{{ $building->code }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $building->status ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                                    {{ $building->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $building->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Locations Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Locations</h2>
                <a href="{{ route('admin.master.locations.index') }}"
                    class="text-blue-600 hover:text-blue-800 transition-colors">Manage
                    →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Building</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="locationsTable">
                        @foreach($locations as $location)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all">
                            <td class="px-6 py-4">{{ $location->name }}</td>
                            <td class="px-6 py-4">{{ $location->building->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                    {{ $location->status ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                                    {{ $location->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $location->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection