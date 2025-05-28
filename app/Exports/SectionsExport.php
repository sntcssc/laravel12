<?php

namespace App\Exports;

use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SectionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Section::with('programme')->get()->map(function ($section) {
            return [
                'name' => $section->name,
                'programme' => $section->programme ? $section->programme->name : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Programme'];
    }
}