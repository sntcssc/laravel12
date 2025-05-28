<?php

namespace App\Livewire\Section;

use App\Services\SectionService;
use Livewire\Component;
use Livewire\WithPagination;

class ListSections extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedSections = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(SectionService $sectionService)
    {
        $filters = ['search' => $this->search];
        $sections = $sectionService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.section.list-sections', [
            'sections' => $sections,
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

    public function bulkDelete(SectionService $sectionService)
    {
        $this->validate([
            'selectedSections' => 'required|array|min:1',
        ]);

        try {
            $sectionService->delete($this->selectedSections);
            $this->selectedSections = [];
            session()->flash('message', 'Selected sections deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, SectionService $sectionService)
    {
        try {
            $sectionService->restore([$id]);
            session()->flash('message', 'Section restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(SectionService $sectionService)
    {
        try {
            return $sectionService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(SectionService $sectionService)
    {
        try {
            return $sectionService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}