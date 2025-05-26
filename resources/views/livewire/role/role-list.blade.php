<div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
    <div x-data="{ showSuccess: @entangle('successMessage'), showError: @entangle('errorMessage') }" class="mb-6">
        <div x-show="showSuccess" x-transition class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-4 rounded-lg mb-4 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button @click="showSuccess = false" class="text-green-800 dark:text-green-200">&times;</button>
        </div>
        <div x-show="showError" x-transition class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 p-4 rounded-lg mb-4 flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button @click="showError = false" class="text-red-800 dark:text-red-200">&times;</button>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <input wire:model.live="search" type="text" placeholder="Search roles..." class="w-full sm:w-64 border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500">
                <label class="flex items-center space-x-2">
                    <input wire:model.live="showTrashed" type="checkbox" class="h-5 w-5 text-indigo-600">
                    <span>Show Deleted</span>
                </label>
            </div>
            <div class="flex space-x-2">
                <button wire:click="exportPdf" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Export PDF</button>
                <button wire:click="exportExcel" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Export Excel</button>
                <a href="{{ route('roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Add Role</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-left">
                        <th class="p-4">
                            <input type="checkbox" wire:model="selectedRoles" wire:click="$toggle('selectedRoles')" class="h-5 w-5 text-indigo-600">
                        </th>
                        <th class="p-4 cursor-pointer hover:text-indigo-600" wire:click="toggleSort('name')">Name</th>
                        <th class="p-4">Permissions</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr class="border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="p-4">
                                <input type="checkbox" value="{{ $role->id }}" wire:model="selectedRoles" class="h-5 w-5 text-indigo-600">
                            </td>
                            <td class="p-4">{{ $role->name }}</td>
                            <td class="p-4">{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                            <td class="p-4 flex space-x-2">
                                <a href="{{ route('roles.edit', $role) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                @if ($role->trashed())
                                    <button wire:click="restore({{ $role->id }})" class="text-green-600 hover:text-green-800">Restore</button>
                                @else
                                    <button wire:click="delete({{ $role->id }})" class="text-red-600 hover:text-red-800">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $roles->links('components.pagination') }}
        </div>

        @if (!empty($selectedRoles))
            <div class="mt-4">
                <button wire:click="bulkDelete" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Delete Selected</button>
            </div>
        @endif
    </div>
</div>