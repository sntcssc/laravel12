<?php

namespace App\Services;

use App\Repositories\ProgrammeRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProgrammesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgrammeService
{
    protected $repository;

    public function __construct(ProgrammeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve programmes: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create programme: ' . $e->getMessage());
        }
    }

    public function update($programme, array $data)
    {
        try {
            return $this->repository->update($programme, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update programme: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete programmes: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore programmes: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find programme: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new ProgrammesExport, 'programmes.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export programmes to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $programmes = $this->repository->getAll([], 'name', 'asc')->get();
            $pdf = Pdf::loadView('exports.programmes', compact('programmes'));
            return $pdf->download('programmes.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export programmes to PDF: ' . $e->getMessage());
        }
    }
}