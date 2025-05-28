<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with(['student', 'programme', 'batch'])->get()->map(function ($attendance) {
            return [
                'student' => $attendance->student->name,
                'programme' => $attendance->programme->name,
                'batch' => $attendance->batch->name,
                'date' => $attendance->date->format('Y-m-d'),
                'status' => $attendance->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Student', 'Programme', 'Batch', 'Date', 'Status'];
    }
}