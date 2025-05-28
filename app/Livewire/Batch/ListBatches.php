<?php

namespace App\Livewire\Batch;

use App\Services\BatchService;
use Livewire\Component;
use Livewire\WithPagination;

class ListBatches extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'year';
    public $sortDirection = 'asc';
    public $selectedBatches = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(BatchService $batchService)
    {
        $filters = ['search' => $this->search];
        $batches = $batchService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.batch.list-batches', [
            'batches' => $batches,
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

    public function bulkDelete(BatchService $batchService)
    {
        $this->validate([
            'selectedBatches' => 'required|array|min:1',
        ]);

        try {
            $batchService->delete($this->selectedBatches);
            $this->selectedBatches = [];
            session()->flash('message', 'Selected batches deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, BatchService $batchService)
    {
        try {
            $batchService->restore([$id]);
            session()->flash('message', 'Batch restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(BatchService $batchService)
    {
        try {
            return $batchService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(BatchService $batchService)
    {
        try {
            return $batchService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}