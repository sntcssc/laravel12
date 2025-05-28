<?php

namespace App\Livewire\Attendance;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Services\AttendanceService;
use App\Models\Attendance;
use Livewire\Component;

class EditAttendance extends Component
{
    public $attendance;
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

    // public function mount($id, AttendanceService $attendanceService)
    // {
    //     $this->attendance = $attendanceService->find($id);
    //     $this->student_id = $this->attendance->student_id;
    //     $this->programme_id = $this->attendance->programme_id;
    //     $this->batch_id = $this->attendance->batch_id;
    //     $this->date = $this->attendance->date->format('Y-m-d');
    //     $this->status = $this->attendance->status;
    // }

    public function mount(Attendance $attendance)
    {
        $this->attendance = $attendance;
        $this->student_id = $this->attendance->student_id;
        $this->programme_id = $this->attendance->programme_id;
        $this->batch_id = $this->attendance->batch_id;
        $this->date = \Carbon\Carbon::parse($this->attendance->date)->format('Y-m-d');
        $this->status = $this->attendance->status;
    }

    public function render()
    {
        $students = Student::select('id', 'name')->get();
        $programmes = Programme::select('id', 'name')->get();
        $batches = Batch::select('id', 'name')->get();
        return view('livewire.attendance.edit', compact('students', 'programmes', 'batches'));
        // ->layout('layouts.app');
    }

    public function save(AttendanceService $attendanceService)
    {
        $this->validate();

        try {
            $attendanceService->update($this->attendance, [
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'date' => $this->date,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Attendance updated successfully.');
            return redirect()->route('attendances.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}