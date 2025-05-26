<?php

namespace App\Exports;

use App\Services\UserService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $userService;
    protected $filters;

    public function __construct(UserService $userService, array $filters = [])
    {
        $this->userService = $userService;
        $this->filters = $filters;
    }

    public function collection()
    {
        // return $this->userService->exportUsers($this->filters);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Staff ID',
            'Email',
            'Phone',
            'Joining Date',
            'Status',
        ];
    }
}