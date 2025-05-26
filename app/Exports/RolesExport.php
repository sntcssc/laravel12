<?php

namespace App\Exports;

use App\Services\RolePermissionService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RolesExport implements FromCollection, WithHeadings
{
    protected $roleService;
    protected $filters;

    public function __construct(RolePermissionService $roleService, array $filters = [])
    {
        $this->roleService = $roleService;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->roleService->exportRoles($this->filters);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Permissions',
        ];
    }
}