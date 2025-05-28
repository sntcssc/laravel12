<?php

namespace App\Services;

use App\Repositories\LeaveRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeavesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveService
{
    protected $repository;

    public function __construct(LeaveRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve leaves: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create leave: ' . $e->getMessage());
        }
    }

    public function update($leave, array $data)
    {
        try {
            return $this->repository->update($leave, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update leave: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete leaves: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore leaves: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find leave: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new LeavesExport, 'leaves.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export leaves to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $leaves = $this->repository->getAll([], 'start_date', 'desc')->get();
            $pdf = Pdf::loadView('exports.leaves', compact('leaves'));
            return $pdf->download('leaves.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export leaves to PDF: ' . $e->getMessage());
        }
    }
}