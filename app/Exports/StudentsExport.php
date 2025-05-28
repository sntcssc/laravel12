<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::select('name', 'email', 'phone', 'address')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Address'];
    }
}