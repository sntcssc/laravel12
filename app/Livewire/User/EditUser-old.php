<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;

class EditUser extends Component
{
    public $userId;
    public $name;
    public $staff_id;
    public $phone;
    public $joining_date;
    public $email;
    public $password;
    public $status;
    public $roles = [];

    // Use a method to define rules dynamically
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'staff_id' => 'required|string|max:50|unique:users,staff_id,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,banned',
            'roles' => 'array',
        ];
    }

    public function mount($user)
    {
        // Handle case where $user is an ID (string or numeric) or a User model
        $user = is_string($user) || is_numeric($user) ? User::findOrFail($user) : $user;

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->staff_id = $user->staff_id;
        $this->phone = $user->phone;
        $this->joining_date = $user->joining_date?->format('Y-m-d');
        $this->email = $user->email;
        $this->status = $user->status;
        $this->roles = $user->roles->pluck('name')->toArray();
    }

    public function render()
    {
        $allRoles = Role::all()->pluck('name', 'id');
        return view('livewire.user.edit-user', compact('allRoles'));
    }

    public function save(UserService $userService)
    {
        $this->validate();

        $user = $userService->findById($this->userId);
        $userService->updateUser($user, [
            'name' => $this->name,
            'staff_id' => $this->staff_id,
            'phone' => $this->phone,
            'joining_date' => $this->joining_date,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status,
            'roles' => $this->roles,
        ]);

        session()->flash('message', 'User updated successfully.');
        return redirect()->route('users.index');
    }
}