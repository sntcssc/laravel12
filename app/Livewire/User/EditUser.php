<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;

class EditUser extends Component
{
    public User $user;
    public $name;
    public $staff_id;
    public $phone;
    public $joining_date;
    public $email;
    public $password;
    public $status;
    public $roles = [];

    // protected $rules = [
    //     'name' => 'required|string|max:255',
    //     'staff_id' => 'required|string|max:50|unique:users,staff_id,{{user.id}}',
    //     'phone' => 'nullable|string|max:20',
    //     'joining_date' => 'nullable|date',
    //     'email' => 'required|email|unique:users,email,{{user.id}}',
    //     'password' => 'nullable|string|min:8',
    //     'status' => 'required|in:active,inactive,banned',
    //     'roles' => 'array',
    // ];

    // Use a method to define rules dynamically
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'staff_id' => 'required|string|max:50|unique:users,staff_id,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,inactive,banned',
            'roles' => 'array',
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;
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

        $userService->updateUser($this->user, [
            'name' => $this->name,
            'staff_id' => $this->staff_id,
            'phone' => $this->phone,
            'joining_date' => $this->joining_date,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status,
            'roles' => $this->roles,
        ]);

        session()->flash('success', 'User updated successfully.');
        return redirect()->route('users.index');
    }
}