<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Enrollment Management</h1>
        <a href="{{ route('enrollments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Enrollment</a>
    </div>

    <!-- Search and Filter -->
    <div class="mb-4">
        <input wire:model.live.debounce.500ms="search" type="text" placeholder="Search enrollments..." class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white dark:border-gray-600">
    </div>

    <!-- Bulk Actions -->
    <div class="mb-4 flex space-x-2">
        <button wire:click="bulkDelete" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure you want to delete the selected enrollments?')">Bulk Delete</button>
        <button wire:click="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Export Excel</button>
        <button wire:click="exportPDF" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Export PDF</button>
    </div>

    <!-- Enrollments Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left">
                        <input type="checkbox" wire:model="selectedEnrollments" class="rounded">
                    </th>
                    <th wire:click="sortBy('student_id')" class="cursor-pointer p-3 text-left font-semibold">
                        Student @if($sortField === 'student_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('programme_id')" class="cursor-pointer p-3 text-left font-semibold">
                        Programme @if($sortField === 'programme_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('batch_id')" class="cursor-pointer p-3 text-left font-semibold">
                        Batch @if($sortField === 'batch_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('section_id')" class="cursor-pointer p-3 text-left font-semibold">
                        Section @if($sortField === 'section_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('enrolled_at')" class="cursor-pointer p-3 text-left font-semibold">
                        Enrolled At @if($sortField === 'enrolled_at') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="p-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enrollments as $enrollment)
                    <tr class="{{ $enrollment->deleted_at ? 'bg-gray-100 dark:bg-gray-600' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors">
                        <td class="p-3">
                            <input type="checkbox" wire:model="selectedEnrollments" value="{{ $enrollment->id }}" class="rounded">
                        </td>
                        <td class="p-2">{{ $enrollment->student->name }}</td>
                        <td class="p-2">{{ $enrollment->programme->name }}</td>
                        <td class="p-2">{{ $enrollment->batch->name }}</td>
                        <td class="p-2">{{ $enrollment->section ? $enrollment->section->name : 'N/A' }}</td>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('Y-m-d') }}
                        </td>
                        <td class="p-2 flex space-x-2">
                            @if ($enrollment->deleted_at)
                                <button wire:click="restore({{ $enrollment->id }})" class="text-green-500 hover:text-green-600 font-medium">Restore</button>
                            @else
                                <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="text-blue-500 hover:text-blue-600 font-medium">Edit</a>
                                <button wire:click="delete({{ $enrollment->id }})" class="text-red-500 hover:text-red-600 font-medium" onclick="return confirm('Are you sure you want to delete this enrollment?')">Delete</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $enrollments->links() }}
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg shadow">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif
</div>