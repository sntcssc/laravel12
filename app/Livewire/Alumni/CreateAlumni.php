<?php

namespace App\Livewire\Alumni;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Services\AlumniService;
use Livewire\Component;

class CreateAlumni extends Component
{
    public $student_id;
    public $programme_id;
    public $batch_id;
    public $completion_date;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'programme_id' => 'required|exists:programmes,id',
        'batch_id' => 'required|exists:batches,id',
        'completion_date' => 'required|date',
    ];

    public function render()
    {
        $students = Student::select('id', 'name')->get();
        $programmes = Programme::select('id', 'name')->get();
        $batches = Batch::select('id', 'name')->get();
        return view('livewire.alumni.create', compact('students', 'programmes', 'batches'));
        // ->layout('layouts.app');
    }

    public function save(AlumniService $alumniService)
    {
        $this->validate();

        try {
            $alumniService->create([
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'completion_date' => $this->completion_date,
            ]);
            session()->flash('message', 'Alumni record created successfully.');
            return redirect()->route('alumni.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}