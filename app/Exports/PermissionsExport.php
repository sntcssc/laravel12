<?php

namespace App\Exports;

use App\Services\RolePermissionService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermissionsExport implements FromCollection, WithHeadings
{
    protected $permissionService;
    protected $filters;

    public function __construct(RolePermissionService $permissionService, array $filters = [])
    {
        $this->permissionService = $permissionService;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->permissionService->exportPermissions($this->filters);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
        ];
    }
}