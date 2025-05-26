<?php

namespace App\Livewire\Permission;

use App\Services\RolePermissionService;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PermissionsExport;
use App\Models\Permission;

class PermissionList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $selectedPermissions = [];
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

    public function render(RolePermissionService $permissionService)
    {
        $filters = [
            'search' => $this->search,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
            'per_page' => $this->perPage,
        ];

        $permissions = $permissionService->getAllPermissions($filters, $this->showTrashed);

        return view('livewire.permission.permission-list', [
            'permissions' => $permissions,
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

    public function delete($permissionId, RolePermissionService $permissionService)
    {
        // $permission = \Spatie\Permission\Models\Permission::findOrFail($permissionId);
        $permission = Permission::findOrFail($permissionId);
        $permissionService->deletePermission($permission);
        session()->flash('success', 'Permission deleted successfully.');
        $this->resetPage();
    }

    public function bulkDelete(RolePermissionService $permissionService)
    {
        $permissionService->bulkDeletePermissions($this->selectedPermissions);
        $this->selectedPermissions = [];
        session()->flash('success', 'Selected permissions deleted successfully.');
        $this->resetPage();
    }

    public function restore($permissionId, RolePermissionService $permissionService)
    {
        $permissionService->restorePermission($permissionId);
        session()->flash('success', 'Permission restored successfully.');
        $this->resetPage();
    }

    public function exportPdf(RolePermissionService $permissionService)
    {
        $filters = ['search' => $this->search];
        $permissions = $permissionService->exportPermissions($filters);
        $pdf = Pdf::loadView('exports.permissions-pdf', ['permissions' => $permissions]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'permissions.pdf');
    }

    public function exportExcel(RolePermissionService $permissionService)
    {
        $filters = ['search' => $this->search];
        return Excel::download(new PermissionsExport($permissionService, $filters), 'permissions.xlsx');
    }
}