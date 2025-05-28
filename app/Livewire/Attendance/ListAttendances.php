<?php

namespace App\Livewire\Attendance;

use App\Services\AttendanceService;
use Livewire\Component;
use Livewire\WithPagination;

class ListAttendances extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $selectedAttendances = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(AttendanceService $attendanceService)
    {
        $filters = ['search' => $this->search];
        $attendances = $attendanceService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.attendance.list-attendances', [
            'attendances' => $attendances,
        ]);
        // ->layout('layouts.app');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function bulkDelete(AttendanceService $attendanceService)
    {
        $this->validate([
            'selectedAttendances' => 'required|array|min:1',
        ]);

        try {
            $attendanceService->delete($this->selectedAttendances);
            $this->selectedAttendances = [];
            session()->flash('message', 'Selected attendances deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, AttendanceService $attendanceService)
    {
        try {
            $attendanceService->restore([$id]);
            session()->flash('message', 'Attendance restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(AttendanceService $attendanceService)
    {
        try {
            return $attendanceService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(AttendanceService $attendanceService)
    {
        try {
            return $attendanceService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}