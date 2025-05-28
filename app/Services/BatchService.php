<?php

namespace App\Services;

use App\Repositories\BatchRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BatchesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BatchService
{
    protected $repository;

    public function __construct(BatchRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve batches: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create batch: ' . $e->getMessage());
        }
    }

    public function update($batch, array $data)
    {
        try {
            return $this->repository->update($batch, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update batch: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete batches: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore batches: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find batch: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new BatchesExport, 'batches.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export batches to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $batches = $this->repository->getAll([], 'year', 'asc')->get();
            $pdf = Pdf::loadView('exports.batches', compact('batches'));
            return $pdf->download('batches.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export batches to PDF: ' . $e->getMessage());
        }
    }
}