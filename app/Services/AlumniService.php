<?php

namespace App\Services;

use App\Repositories\AlumniRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumniService
{
    protected $repository;

    public function __construct(AlumniRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve alumni: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create alumni record: ' . $e->getMessage());
        }
    }

    public function update($alumni, array $data)
    {
        try {
            return $this->repository->update($alumni, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update alumni record: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete alumni records: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore alumni records: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find alumni record: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new AlumniExport, 'alumni.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export alumni to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $alumni = $this->repository->getAll([], 'completion_date', 'desc')->get();
            $pdf = Pdf::loadView('exports.alumni', compact('alumni'));
            return $pdf->download('alumni.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export alumni to PDF: ' . $e->getMessage());
        }
    }
}