<?php

namespace App\Livewire\Role;

use App\Services\RolePermissionService;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RolesExport;
use App\Models\Role;

class RoleList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $selectedRoles = [];
    public $showTrashed = false;

    protected $queryString = ['search', 'sortBy', 'sortDirection', 'showTrashed'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedShowTrashed()
    {
        $this->resetPage();
    }

    public function render(RolePermissionService $roleService)
    {
        $filters = [
            'search' => $this->search,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
            'per_page' => $this->perPage,
        ];

        $roles = $roleService->getAllRoles($filters, $this->showTrashed);

        return view('livewire.role.role-list', [
            'roles' => $roles,
        ]);
    }

    public function toggleSort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function delete($roleId, RolePermissionService $roleService)
    {
        // $role = \Spatie\Permission\Models\Role::findOrFail($roleId);
        $role = Role::findOrFail($roleId);
        $roleService->deleteRole($role);
        session()->flash('success', 'Role deleted successfully.');
        $this->resetPage();
    }

    public function bulkDelete(RolePermissionService $roleService)
    {
        $roleService->bulkDeleteRoles($this->selectedRoles);
        $this->selectedRoles = [];
        session()->flash('success', 'Selected roles deleted successfully.');
        $this->resetPage();
    }

    public function restore($roleId, RolePermissionService $roleService)
    {
        $roleService->restoreRole($roleId);
        session()->flash('success', 'Role restored successfully.');
        $this->resetPage();
    }

    public function exportPdf(RolePermissionService $roleService)
    {
        $filters = ['search' => $this->search];
        $roles = $roleService->exportRoles($filters);
        $pdf = Pdf::loadView('exports.roles-pdf', ['roles' => $roles]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'roles.pdf');
    }

    public function exportExcel(RolePermissionService $roleService)
    {
        $filters = ['search' => $this->search];
        return Excel::download(new RolesExport($roleService, $filters), 'roles.xlsx');
    }
}