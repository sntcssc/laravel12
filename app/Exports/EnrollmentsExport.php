<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnrollmentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Enrollment::with(['student', 'programme', 'batch', 'section'])->get()->map(function ($enrollment) {
            return [
                'student' => $enrollment->student->name,
                'programme' => $enrollment->programme->name,
                'batch' => $enrollment->batch->name,
                'section' => $enrollment->section ? $enrollment->section->name : 'N/A',
                'enrolled_at' => $enrollment->enrolled_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Student', 'Programme', 'Batch', 'Section', 'Enrolled At'];
    }
}