<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Section</h1>

    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input wire:model="name" type="text" id="name" class="mt-1 block w-full p-2 border rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="programme_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Programme</label>
            <select wire:model="programme_id" id="programme_id" class="mt-1 block w-full p-2 border rounded dark:bg-gray-700 dark:text-white dark:border-gray-600">
                <option value="">Select Programme</option>
                @foreach ($programmes as $programme)
                    <option value="{{ $programme->id }}">{{ $programme->name }}</option>
                @endforeach
            </select>
            @error('programme_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-2">
            <button wire:click="save" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
            <a href="{{ route('sections.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
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