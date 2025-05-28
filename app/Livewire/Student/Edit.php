<?php

namespace App\Livewire\Student;

use App\Services\StudentService;
use App\Models\Student;
use Livewire\Component;

class Edit extends Component
{
    public $student;
    public $name;
    public $email;
    public $phone;
    public $address;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:students,email,{{ $student->id }}',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ];

    // public function mount($id, StudentService $studentService)
    // {
    //     $this->student = $studentService->find($id);
    //     $this->name = $this->student->name;
    //     $this->email = $this->student->email;
    //     $this->phone = $this->student->phone;
    //     $this->address = $this->student->address;
    // }

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->name = $this->student->name;
        $this->email = $this->student->email;
        $this->phone = $this->student->phone;
        $this->address = $this->student->address;
    }

    public function render()
    {
        return view('livewire.student.edit-student');
        // ->layout('layouts.app');
    }

    public function save(StudentService $studentService)
    {
        $this->validate();

        try {
            $studentService->update($this->student, [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            session()->flash('message', 'Student updated successfully.');
            return redirect()->route('students.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}