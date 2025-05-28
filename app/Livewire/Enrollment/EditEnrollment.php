<?php

namespace App\Livewire\Enrollment;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Models\Section;
use App\Services\EnrollmentService;
use App\Models\Enrollment;
use Livewire\Component;

class EditEnrollment extends Component
{
    public $enrollment;
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

    // public function mount($id, EnrollmentService $enrollmentService)
    // {
    //     $this->enrollment = $enrollmentService->find($id);
    //     $this->student_id = $this->enrollment->student_id;
    //     $this->programme_id = $this->enrollment->programme_id;
    //     $this->batch_id = $this->enrollment->batch_id;
    //     $this->section_id = $this->enrollment->section_id;
    //     $this->enrolled_at = $this->enrollment->enrolled_at->format('Y-m-d');
    //     $this->updatedProgrammeId($this->programme_id);
    // }

    public function mount(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
        $this->student_id = $this->enrollment->student_id;
        $this->programme_id = $this->enrollment->programme_id;
        $this->batch_id = $this->enrollment->batch_id;
        $this->section_id = $this->enrollment->section_id;
        // $this->enrolled_at = $this->enrollment->enrolled_at->format('Y-m-d');
        // If it's a string, manually parse it as a Carbon instance
        $this->enrolled_at = \Carbon\Carbon::parse($this->enrollment->enrolled_at)->format('Y-m-d');
        $this->updatedProgrammeId($this->programme_id);
    }

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
        return view('livewire.enrollment.edit', compact('students', 'programmes', 'batches', 'sections'));
        // ->layout('layouts.app');
    }

    public function save(EnrollmentService $enrollmentService)
    {
        $this->validate();

        try {
            $enrollmentService->update($this->enrollment, [
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'section_id' => $this->section_id,
                'enrolled_at' => $this->enrolled_at,
            ]);
            session()->flash('message', 'Enrollment updated successfully.');
            return redirect()->route('enrollments.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}