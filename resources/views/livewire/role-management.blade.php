<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}

<div x-data="{ showNotification: false, message: '', type: '' }" x-init="
    window.addEventListener('notify', event => {
        showNotification = true;
        message = event.detail.message;
        type = event.detail.type;
        setTimeout(() => showNotification = false, 3000);
    });
" class="min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
    <!-- Notification -->
    <div x-show="showNotification" :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning'
    }" class="fixed top-4 right-4 p-4 text-white rounded-lg shadow-lg transition-opacity duration-300">
        <span x-text="message"></span>
    </div>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Role Management</h1>
            <button wire:click="$toggle('showTrashed')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                {{ $showTrashed ? 'Show Active' : 'Show Trashed' }}
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-4 flex gap-4 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Search roles..." class="p-2 rounded border dark:bg-gray-800 dark:text-white focus:ring focus:ring-blue-300">
            <button wire:click="export('pdf')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Export PDF</button>
            <button wire:click="export('excel')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Export Excel</button>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
                        <input wire:model="name" type="text" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Permissions</label>
                        @if(empty($permissions))
                            <p class="text-red-500 text-sm">No permissions available. Please create permissions first.</p>
                        @else
                            <select wire:model="permissions" multiple class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                            @error('permissions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>
                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    {{ $editId ? 'Update' : 'Create' }} Role
                </button>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="mb-4 flex gap-4 flex-wrap">
            <button wire:click="bulkDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Bulk Delete</button>
        </div>

        <!-- Roles Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @if($roles->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No roles found.</p>
            @else
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-700 dark:text-gray-300">
                            <th><input type="checkbox" wire:model="selectedRoles" wire:change="$set('selectedRoles', $roles->pluck('id')->toArray())"></th>
                            <th wire:click="sortBy('name')" class="cursor-pointer">Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr class="border-t dark:border-gray-700">
                                <td><input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"></td>
                                <td class="text-gray-900 dark:text-white">{{ $role->name }}</td>
                                <td class="text-gray-900 dark:text-white">{{ implode(', ', $role->getPermissionNames()->toArray()) }}</td>
                                <td>
                                    @if($role->trashed())
                                        <button wire:click="restore({{ $role->id }})" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Restore</button>
                                    @else
                                        <button wire:click="edit({{ $role->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</button>
                                        <button wire:click="delete({{ $role->id }})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $roles->links() }}
            @endif
        </div>
    </div>
</div>
</div>
