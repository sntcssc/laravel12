<?php

namespace App\Livewire\Enrollment;

use App\Services\EnrollmentService;
use Livewire\Component;
use Livewire\WithPagination;

class ListEnrollments extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'enrolled_at';
    public $sortDirection = 'desc';
    public $selectedEnrollments = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(EnrollmentService $enrollmentService)
    {
        $filters = ['search' => $this->search];
        $enrollments = $enrollmentService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.enrollment.list-enrollments', [
            'enrollments' => $enrollments,
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

    public function bulkDelete(EnrollmentService $enrollmentService)
    {
        $this->validate([
            'selectedEnrollments' => 'required|array|min:1',
        ]);

        try {
            $enrollmentService->delete($this->selectedEnrollments);
            $this->selectedEnrollments = [];
            session()->flash('message', 'Selected enrollments deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, EnrollmentService $enrollmentService)
    {
        try {
            $enrollmentService->restore([$id]);
            session()->flash('message', 'Enrollment restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(EnrollmentService $enrollmentService)
    {
        try {
            return $enrollmentService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(EnrollmentService $enrollmentService)
    {
        try {
            return $enrollmentService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}