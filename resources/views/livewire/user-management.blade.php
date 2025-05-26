<div>
    {{-- Be like water. --}}

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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            <button wire:click="$toggle('showTrashed')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                {{ $showTrashed ? 'Show Active' : 'Show Trashed' }}
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-4 flex gap-4 flex-wrap">
            <input wire:model.live="search" type="text" placeholder="Search users..." class="p-2 rounded border dark:bg-gray-800 dark:text-white focus:ring focus:ring-blue-300">
            <select wire:model="filterStatus" class="p-2 rounded border dark:bg-gray-800 dark:text-white">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="banned">Banned</option>
            </select>
            <button wire:click="export('pdf')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Export PDF</button>
            <button wire:click="export('excel')" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Export Excel</button>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input wire:model="name" type="text" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff ID</label>
                        <input wire:model="staff_id" type="text" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('staff_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input wire:model="phone" type="text" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Joining Date</label>
                        <input wire:model="joining_date" type="date" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('joining_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input wire:model="email" type="email" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input wire:model="password" type="password" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select wire:model="status" class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="banned">Banned</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                        @if(empty($roles))
                            <p class="text-red-500 text-sm">No roles available. Please create roles first.</p>
                        @else
                            <select wire:model="roles" multiple class="mt-1 p-2 w-full rounded border dark:bg-gray-700 dark:text-white focus:ring focus:ring-blue-300">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('roles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>
                </div>
                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    {{ $editId ? 'Update' : 'Create' }} User
                </button>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div class="mb-4 flex gap-4 flex-wrap">
            @if(empty($roles))
                <p class="text-red-500 text-sm">No roles available for bulk assignment.</p>
            @else
                <select wire:model="roles" multiple class="p-2 rounded border dark:bg-gray-800 dark:text-white focus:ring focus:ring-blue-300">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <button wire:click="bulkAssignRoles" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Assign Roles</button>
            @endif
            <button wire:click="bulkDelete" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Bulk Delete</button>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @if($users->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No users found.</p>
            @else
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-700 dark:text-gray-300">
                            <th><input type="checkbox" wire:model="selectedUsers" wire:change="$set('selectedUsers', $users->pluck('id')->toArray())"></th>
                            <th wire:click="sortBy('name')" class="cursor-pointer">Name</th>
                            <th wire:click="sortBy('staff_id')" class="cursor-pointer">Staff ID</th>
                            <th wire:click="sortBy('email')" class="cursor-pointer">Email</th>
                            <th wire:click="sortBy('status')" class="cursor-pointer">Status</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-t dark:border-gray-700">
                                <td><input type="checkbox" wire:model="selectedUsers" value="{{ $user->id }}"></td>
                                <td class="text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="text-gray-900 dark:text-white">{{ $user->staff_id }}</td>
                                <td class="text-gray-900 dark:text-white">{{ $user->email }}</td>
                                <td class="text-gray-900 dark:text-white">{{ $user->status }}</td>
                                <td class="text-gray-900 dark:text-white">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                <td>
                                    @if($user->trashed())
                                        <button wire:click="restore({{ $user->id }})" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">Restore</button>
                                    @else
                                        <button wire:click="edit({{ $user->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">Edit</button>
                                        <button wire:click="delete({{ $user->id }})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            @endif
        </div>
    </div>
</div>

</div>
