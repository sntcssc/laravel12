<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public $name;
    public $staff_id;
    public $phone;
    public $joining_date;
    public $email;
    public $password;
    public $status = 'active';
    public $roles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'staff_id' => 'required|string|max:50|unique:users,staff_id',
        'phone' => 'nullable|string|max:20',
        'joining_date' => 'nullable|date',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'status' => 'required|in:active,inactive,banned',
        'roles' => 'array',
    ];

    public function render()
    {
        $allRoles = Role::all()->pluck('name', 'id');
        return view('livewire.user.create-user', compact('allRoles'));
    }

    public function save(UserService $userService)
    {
        $this->validate();

        $userService->createUser([
            'name' => $this->name,
            'staff_id' => $this->staff_id,
            'phone' => $this->phone,
            'joining_date' => $this->joining_date,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status,
            'roles' => $this->roles,
        ]);

        session()->flash('message', 'User created successfully.');
        return redirect()->route('users.index');
    }
}