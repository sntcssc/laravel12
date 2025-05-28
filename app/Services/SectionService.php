<?php

namespace App\Services;

use App\Repositories\SectionRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SectionsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SectionService
{
    protected $repository;

    public function __construct(SectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve sections: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create section: ' . $e->getMessage());
        }
    }

    public function update($section, array $data)
    {
        try {
            return $this->repository->update($section, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update section: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete sections: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore sections: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find section: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new SectionsExport, 'sections.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export sections to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $sections = $this->repository->getAll([], 'name', 'asc')->get();
            $pdf = Pdf::loadView('exports.sections', compact('sections'));
            return $pdf->download('sections.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export sections to PDF: ' . $e->getMessage());
        }
    }
}