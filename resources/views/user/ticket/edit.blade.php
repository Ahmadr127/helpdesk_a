@extends('user.layouts.app')

@section('title', 'Edit Tiket')

@section('content')
<!-- Include the notification partial -->
@include('user.partials.notification')

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('user.ticket.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Tiket
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Edit Tiket #{{ $ticket->ticket_number }}</h2>

            <form action="{{ route('user.ticket.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                    <select name="department_id" id="department_id"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('department_id', $ticket->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('department_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="building_id" class="block text-sm font-medium text-gray-700 mb-1">Gedung</label>
                    <select name="building_id" id="building_id"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required onchange="updateLocations()">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                        <option value="{{ $building->id }}"
                            {{ old('building_id', $ticket->building_id) == $building->id ? 'selected' : '' }}>
                            {{ $building->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('building_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="location_id" id="location_id"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required>
                        <option value="">Pilih Lokasi</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    @error('location_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required>{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select name="priority" id="priority"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#e8f5e9] focus:border-[#2e7d32] transition-colors"
                        required>
                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Rendah
                        </option>
                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>
                            Sedang</option>
                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>Tinggi
                        </option>
                    </select>
                    @error('priority')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        Perbarui Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Store all locations data
const allLocations = @json($locations);

function updateLocations() {
    const buildingSelect = document.getElementById('building_id');
    const locationSelect = document.getElementById('location_id');
    const selectedBuildingId = buildingSelect.value;

    // Clear current options
    locationSelect.innerHTML = '<option value="">Pilih Lokasi</option>';

    // Filter locations for selected building
    const filteredLocations = allLocations.filter(location =>
        location.building_id == selectedBuildingId
    );

    // Add filtered locations to select
    filteredLocations.forEach(location => {
        const option = document.createElement('option');
        option.value = location.id;
        option.textContent = location.name;
        locationSelect.appendChild(option);
    });
}

// Initialize locations on page load
document.addEventListener('DOMContentLoaded', function() {
    updateLocations();

    // Set previously selected location if any
    const oldLocationId = "{{ old('location_id', $ticket->location_id) }}";
    if (oldLocationId) {
        setTimeout(() => {
            const locationSelect = document.getElementById('location_id');
            if (locationSelect.querySelector(`option[value="${oldLocationId}"]`)) {
                locationSelect.value = oldLocationId;
            }
        }, 100);
    }
});
</script>
@endpush
@endsection