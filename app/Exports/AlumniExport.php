<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AlumniExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Alumni::with(['student', 'programme', 'batch'])->get()->map(function ($alumni) {
            return [
                'student' => $alumni->student->name,
                'programme' => $alumni->programme->name,
                'batch' => $alumni->batch->name,
                'completion_date' => $alumni->completion_date->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Student', 'Programme', 'Batch', 'Completion Date'];
    }
}