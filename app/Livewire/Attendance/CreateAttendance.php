<?php

namespace App\Livewire\Attendance;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Services\AttendanceService;
use Livewire\Component;

class CreateAttendance extends Component
{
    public $student_id;
    public $programme_id;
    public $batch_id;
    public $date;
    public $status;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'programme_id' => 'required|exists:programmes,id',
        'batch_id' => 'required|exists:batches,id',
        'date' => 'required|date',
        'status' => 'required|in:present,absent,late',
    ];

    public function render()
    {
        $students = Student::select('id', 'name')->get();
        $programmes = Programme::select('id', 'name')->get();
        $batches = Batch::select('id', 'name')->get();
        return view('livewire.attendance.create', compact('students', 'programmes', 'batches'));
        // ->layout('layouts.app');
    }

    public function save(AttendanceService $attendanceService)
    {
        $this->validate();

        try {
            $attendanceService->create([
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'date' => $this->date,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Attendance created successfully.');
            return redirect()->route('attendances.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}