<?php

namespace App\Livewire\Leave;

use App\Models\Student;
use App\Models\Programme;
use App\Models\Batch;
use App\Services\LeaveService;
use App\Models\Leave;
use Livewire\Component;

class EditLeave extends Component
{
    public $leave;
    public $student_id;
    public $programme_id;
    public $batch_id;
    public $start_date;
    public $end_date;
    public $reason;
    public $status;

    protected $rules = [
        'student_id' => 'required|exists:students,id',
        'programme_id' => 'required|exists:programmes,id',
        'batch_id' => 'required|exists:batches,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required|string',
        'status' => 'required|in:pending,approved,rejected',
    ];

    // public function mount($id, LeaveService $leaveService)
    // {
    //     $this->leave = $leaveService->find($id);
    //     $this->student_id = $this->leave->student_id;
    //     $this->programme_id = $this->leave->programme_id;
    //     $this->batch_id = $this->leave->batch_id;
    //     $this->start_date = \Carbon\Carbon::parse($this->leave->start_date)->format('Y-m-d');
    //     $this->end_date = \Carbon\Carbon::parse($this->leave->end_date)->format('Y-m-d');
    //     $this->reason = $this->leave->reason;
    //     $this->status = $this->leave->status;
    // }

    public function mount(Leave $leave)
    {
        $this->leave = $leave;
        $this->student_id = $this->leave->student_id;
        $this->programme_id = $this->leave->programme_id;
        $this->batch_id = $this->leave->batch_id;
        $this->start_date = \Carbon\Carbon::parse($this->leave->start_date)->format('Y-m-d');
        $this->end_date = \Carbon\Carbon::parse($this->leave->end_date)->format('Y-m-d');
        $this->reason = $this->leave->reason;
        $this->status = $this->leave->status;
    }

    public function render()
    {
        $students = Student::select('id', 'name')->get();
        $programmes = Programme::select('id', 'name')->get();
        $batches = Batch::select('id', 'name')->get();
        return view('livewire.leave.edit', compact('students', 'programmes', 'batches'));
        // ->layout('layouts.app');
    }

    public function save(LeaveService $leaveService)
    {
        $this->validate();

        try {
            $leaveService->update($this->leave, [
                'student_id' => $this->student_id,
                'programme_id' => $this->programme_id,
                'batch_id' => $this->batch_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'reason' => $this->reason,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Leave updated successfully.');
            return redirect()->route('leaves.index');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}