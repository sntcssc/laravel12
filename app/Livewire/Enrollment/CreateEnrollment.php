<?php

namespace App\Livewire\Enrollment;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Models\Section;
use App\Services\EnrollmentService;
use Livewire\Component;

class CreateEnrollment extends Component
{
    public $student_id;
    public $programme_id;
    public $batch_id;
    public $section_id;
    public $enrolled_at;
    public $showSection = false;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'programme_id' => 'required|exists:programmes,id',
        'batch_id' => 'required|exists:batches,id',
        'section_id' => 'nullable|exists:sections,id',
        'enrolled_at' => 'required|date',
    ];

    public function updatedProgrammeId($value)
    {
        $programme = Programme::find($value);
        $this->showSection = $programme && $programme->name === 'Composite Course';
        if (!$this->showSection) {
            $this->section_id = null;
        }
    }

    public function render()
    {
        $students = Student::select('id', 'name')->get();
        $programmes = Programme::select('id', 'name')->get();
        $batches = Batch::select('id', 'name')->get();
        $sections = Section::select('id', 'name')->where('programme_id', $this->programme_id)->get();
        return view('livewire.enrollment.create', compact('students', 'programmes', 'batches', 'sections'));
        // ->layout('layouts.app');
    }

    public function save(EnrollmentService $enrollmentService)
    {
        $this->validate();

        try {
            $enrollmentService->create([
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'section_id' => $this->section_id,
                'enrolled_at' => $this->enrolled_at,
            ]);
            session()->flash('message', 'Enrollment created successfully.');
            return redirect()->route('enrollments.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}