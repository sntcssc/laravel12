<?php

namespace App\Exports;

use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeavesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Leave::with(['student', 'programme', 'batch'])->get()->map(function ($leave) {
            return [
                'student' => $leave->student->name,
                'programme' => $leave->programme->name,
                'batch' => $leave->batch->name,
                'start_date' => $leave->start_date->format('Y-m-d'),
                'end_date' => $leave->end_date->format('Y-m-d'),
                'reason' => $leave->reason,
                'status' => $leave->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Student', 'Programme', 'Batch', 'Start Date', 'End Date', 'Reason', 'Status'];
    }
}