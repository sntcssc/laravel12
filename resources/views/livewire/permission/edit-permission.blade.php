<div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Edit Permission</h2>

    <div x-data="{ showSuccess: @entangle('successMessage'), showError: @entangle('errorMessage') }" class="mb-6">
        <div x-show="showSuccess" x-transition class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-4 rounded-lg mb-4 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button @click="showSuccess = false" class="text-green-800 dark:text-green-200">&times;</button>
        </div>
        <div x-show="showError" x-transition class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 p-4 rounded-lg mb-4 flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button @click="showError = false" class="text-red-800 dark:text-red-200">&times;</button>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permission Name</label>
                <input wire:model="name" type="text" id="name" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update</button>
                <a href="{{ route('permissions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>