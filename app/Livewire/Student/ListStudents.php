<?php

namespace App\Livewire\Student;

use App\Services\StudentService;
use Livewire\Component;
use Livewire\WithPagination;

class ListStudents extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedStudents = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(StudentService $studentService)
    {
        $filters = ['search' => $this->search];
        $students = $studentService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.student.list-students', [
            'students' => $students,
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

    public function bulkDelete(StudentService $studentService)
    {
        $this->validate([
            'selectedStudents' => 'required|array|min:1',
        ]);

        try {
            $studentService->delete($this->selectedStudents);
            $this->selectedStudents = [];
            session()->flash('message', 'Selected students deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, StudentService $studentService)
    {
        try {
            $studentService->restore([$id]);
            session()->flash('message', 'Student restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(StudentService $studentService)
    {
        try {
            return $studentService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(StudentService $studentService)
    {
        try {
            return $studentService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}