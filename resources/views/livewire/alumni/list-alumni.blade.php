<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Alumni Management</h1>

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Alumni Management</h1>
        <a href="{{ route('alumni.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Alumni Record</a>
    </div>

    <!-- Search and Filter -->
    <div class="mb-4">
        <input wire:model.live.debounce="500ms" type="text" placeholder="Search alumni..." class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-white dark:border-gray-600">
    </div>

    <!-- Bulk Actions -->
    <div class="mb-4 flex space-x-2">
        <button wire:click="bulkDelete" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure you want to delete the selected alumni records?')">Bulk Delete</button>
        <button wire:click="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Export Excel</button>
        <button wire:click="exportPDF" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Export PDF</button>
    </div>

    <!-- Alumni Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left">
                        <input type="checkbox" wire:model="selectedAlumni" class="rounded">
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
                    <th wire:click="sortBy('completion_date')" class="cursor-pointer p-3 text-left font-semibold">
                        Completion Date @if($sortField === 'completion_date') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="p-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alumnis as $alumni)
                    <tr class="{{ $alumni->deleted_at ? 'bg-gray-100 dark:bg-gray-600' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors">
                        <td class="p-3">
                            <input type="checkbox" wire:model="selectedAlumni" value="{{ $alumni->id }}" class="rounded">
                        </td>
                        <td class="p-2">{{ $alumni->student->name }}</td>
                        <td class="p-2">{{ $alumni->programme->name }}</td>
                        <td class="p-2">{{ $alumni->batch->name }}</td>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($alumni->completion_date)->format('Y-m-d') }}
                        </td>
                        <td class="p-2 flex space-x-2">
                            @if ($alumni->deleted_at)
                                <button wire:click="restore({{ $alumni->id }})" class="text-green-600 hover:text-green-700 font-medium">Restore</button>
                            @else
                                <a href="{{ route('alumni.edit', $alumni->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                <button wire:click="delete({{ $alumni->id }})" class="text-red-600 hover:text-red-700 font-medium" onclick="return confirm('Are you sure you want to delete this alumni record?')">Delete</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $alumnis->links() }}
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