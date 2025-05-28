<?php

namespace App\Exports;

use App\Models\Batch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BatchesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Batch::select('year', 'name')->get();
    }

    public function headings(): array
    {
        return ['Year', 'Name'];
    }
}