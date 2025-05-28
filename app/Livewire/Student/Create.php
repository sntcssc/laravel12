<?php

namespace App\Livewire\Student;

use App\Services\StudentService;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $email;
    public $phone;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ];

    public function render()
    {
        return view('livewire.student.create-student');
        // ->layout('layouts.app');
    }

    public function save(StudentService $studentService)
    {
        $this->validate();

        try {
            $studentService->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            session()->flash('message', 'Student created successfully.');
            return redirect()->route('students.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}