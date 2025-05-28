<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-6">Edit Enrolment</h1>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student Name</label>
            <select wire:model="student_id" id="student_id" class="mt-1 block w-full p-2 border bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a Student</option>
                @foreach ($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id}}') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="programme_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Programme</label>
            <select wire:model="programme_id" id="programme_id" class="mt-1 block w-full p-2 border bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select a Programme</option>
                @foreach ($programmes as $programme)
                    <option value="{{ $programme->id }}">{{ $programme->name }}</option>
                @endforeach
            </select>
            @error('programme_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="batch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batch</label>
            <select wire:model="batch_id" id="batch_id" class="mt-1 block w-full p-2 border bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Batch</option>
                @foreach ($batches as $batch)
                }
                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
            @error('batch_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        @if ($showSection)
            <div class="mb-6">
                <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section</label>
                <select wire:model="section_id" id="section_id" class="mt-1 block w-full p-2 border rounded bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a Section</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('section_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="mb-6">
            <label for="enrolled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enrolled At</label>
            <input wire:model="enrolled_at" type="date" id="enrolled_at" class="mt-1 block w-full p-2 border bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            @error('enrolled_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-2">
            <button wire:click="save" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Save</button>
            <a href="{{ route('enrollments.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">Cancel</a>
        </div>
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