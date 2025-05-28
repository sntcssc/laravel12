<?php

namespace App\Livewire\Programme;

use App\Services\ProgrammeService;
use Livewire\Component;
use Livewire\WithPagination;

class ListProgrammes extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedProgrammes = [];
    public $selectAll = false; // New property for select all checkbox
    protected $programmeService;

    public function boot(ProgrammeService $programmeService)
    {
        $this->programmeService = $programmeService;
    }

    // Use $this->programmeService in methods like toggleSelectAll
    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedProgrammes = $this->programmeService->getAll(['search' => $this->search], $this->sortField, $this->sortDirection)
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedProgrammes = [];
        }
    }

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(ProgrammeService $programmeService)
    {
        $filters = ['search' => $this->search];
        $programmes = $programmeService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.programme.list-programmes', [
            'programmes' => $programmes,
        ]);
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

    // public function toggleSelectAll(ProgrammeService $programmeService)
    // {
    //     if ($this->selectAll) {
    //         $this->selectedProgrammes = $programmeService->getAll(['search' => $this->search], $this->sortField, $this->sortDirection)
    //             ->pluck('id')
    //             ->toArray();
    //     } else {
    //         $this->selectedProgrammes = [];
    //     }
    // }

    public function updatedSelectedProgrammes()
    {
        // Optional: Update selectAll state based on selectedProgrammes
        $allProgrammes = $this->programmeService->getAll(['search' => $this->search], $this->sortField, $this->sortDirection)
            ->pluck('id')
            ->toArray();
        $this->selectAll = count($this->selectedProgrammes) === count($allProgrammes);
    }

    public function bulkDelete(ProgrammeService $programmeService)
    {
        $this->validate([
            'selectedProgrammes' => 'required|array|min:1',
        ]);

        try {
            $programmeService->delete($this->selectedProgrammes);
            $this->selectedProgrammes = [];
            $this->selectAll = false; // Reset selectAll after bulk delete
            session()->flash('message', 'Selected programmes deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, ProgrammeService $programmeService)
    {
        try {
            $programmeService->restore([$id]);
            session()->flash('message', 'Programme restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(ProgrammeService $programmeService)
    {
        try {
            return $programmeService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(ProgrammeService $programmeService)
    {
        try {
            return $programmeService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}