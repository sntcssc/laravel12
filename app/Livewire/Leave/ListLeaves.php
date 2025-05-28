<?php

namespace App\Livewire\Leave;

use App\Services\LeaveService;
use Livewire\Component;
use Livewire\WithPagination;

class ListLeaves extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'start_date';
    public $sortDirection = 'desc';
    public $selectedLeaves = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function render(LeaveService $leaveService)
    {
        $filters = ['search' => $this->search];
        $leaves = $leaveService->getAll($filters, $this->sortField, $this->sortDirection);

        return view('livewire.leave.list-leaves', [
            'leaves' => $leaves,
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

    public function bulkDelete(LeaveService $leaveService)
    {
        $this->validate([
            'selectedLeaves' => 'required|array|min:1',
        ]);

        try {
            $leaveService->delete($this->selectedLeaves);
            $this->selectedLeaves = [];
            session()->flash('message', 'Selected leaves deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function restore($id, LeaveService $leaveService)
    {
        try {
            $leaveService->restore([$id]);
            session()->flash('message', 'Leave restored successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportExcel(LeaveService $leaveService)
    {
        try {
            return $leaveService->exportToExcel();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function exportPDF(LeaveService $leaveService)
    {
        try {
            return $leaveService->exportToPDF();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}