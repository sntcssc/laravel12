<?php

namespace App\Livewire\Alumni;

use App\Services\AlumniService;
use Livewire\Component;
use Livewire\WithPagination;

class ListAlumni extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'completion_date';
    public $sortDirection = 'desc';
    public $selectedAlumni = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(AlumniService $alumniService)
    {
        $filters = ['search' => $this->search];
        $alumnis = $alumniService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.alumni.list-alumni', [
            'alumnis' => $alumnis,
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

    public function bulkDelete(AlumniService $alumniService)
    {
        $this->validate([
            'selectedAlumni' => 'required|array|min:1',
        ]);

        try {
            $alumniService->delete($this->selectedAlumni);
            $this->selectedAlumni = [];
            session()->flash('message', 'Selected alumni records deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, AlumniService $alumniService)
    {
        try {
            $alumniService->restore([$id]);
            session()->flash('message', 'Alumni record restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(AlumniService $alumniService)
    {
        try {
            return $alumniService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(AlumniService $alumniService)
    {
        try {
            return $alumniService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}