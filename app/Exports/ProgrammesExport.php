<?php

namespace App\Exports;

use App\Models\Programme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProgrammesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Programme::select('name', 'description')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Description'];
    }
}