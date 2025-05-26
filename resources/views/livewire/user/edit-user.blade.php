<div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Edit User</h2>

    <div x-data="{ showSuccess: @entangle('successMessage'), showError: @entange('errorMessage') }" class="mb-6">
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
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input wire:model="name" type="text" id="name" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="staff_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff ID</label>
                <input wire:model="staff_id" type="text" id="staff_id" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('staff_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                <input wire:model="phone" type="text" id="phone" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="joining_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Joining Date</label>
                <input wire:model="joining_date" type="date" id="joining_date" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('joining_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input wire:model="email" type="email" id="email" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password (leave blank to keep unchanged)</label>
                <input wire:model="password" type="password" id="password" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select wire:model="status" id="status" class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="banned">Banned</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($allRoles as $id => $name)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="roles" value="{{ $name }}" class="h-5 w-5 text-indigo-600">
                            <span>{{ $name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update</button>
                <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>