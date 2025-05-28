<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Batch Management</h1>
        <a href="{{ route('batches.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Batch</a>
    </div>

    <!-- Search and Filter -->
    <div class="mb-4">
        <input wire:model.live.debounce.500ms="search" type="text" placeholder="Search batches..." class="w-full p-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600">
    </div>

    <!-- Bulk Actions -->
    <div class="mb-4 flex space-x-2">
        <button wire:click="bulkDelete" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">Bulk Delete</button>
        <button wire:click="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Export Excel</button>
        <button wire:click="exportPDF" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">Export PDF</button>
    </div>

    <!-- Batches Table -->
    <table class="min-w-full bg-white dark:bg-gray-800 border dark:border-gray-700">
        <thead>
            <tr>
                <th class="p-2"><input type="checkbox" wire:model="selectedBatches" ></th>
                <th wire:click="sortBy('year')" class="cursor-pointer p-2">Year @if($sortField === 'year') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif</th>
                <th wire:click="sortBy('name')" class="cursor-pointer p-2">Name @if($sortField === 'name') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batches as $batch)
                <tr class="{{ $batch->deleted_at ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                    <td class="p-2"><input type="checkbox" wire:model="selectedBatches" value="{{ $batch->id }}"></td>
                    <td class="p-2">{{ $batch->year }}</td>
                    <td class="p-2">{{ $batch->name }}</td>
                    <td class="p-2">
                        @if ($batch->deleted_at)
                            <button wire:click="restore({{ $batch->id }})" class="text-green-500 hover:text-green-600">Restore</button>
                        @else
                            <a href="{{ route('batches.edit', $batch->id) }}" class="text-blue-500 hover:text-blue-600">Edit</a>
                            <button wire:click="delete({{ $batch->id }})" class="text-red-500 hover:text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $batches->links() }}
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mt-4 p-2 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mt-4 p-2 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>