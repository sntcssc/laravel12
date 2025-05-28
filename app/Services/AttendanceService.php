<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceService
{
    protected $repository;

    public function __construct(AttendanceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve attendances: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create attendance: ' . $e->getMessage());
        }
    }

    public function update($attendance, array $data)
    {
        try {
            return $this->repository->update($attendance, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update attendance: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete attendances: ' . $e->getMessage());
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
            throw new \Exception('Failed to find attendance: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new AttendancesExport, 'attendances.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export attendances to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $attendances = $this->repository->getAll([], 'date', 'desc')->get();
            $pdf = Pdf::loadView('exports.attendances', compact('attendances'));
            return $pdf->download('attendances.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export attendances to PDF: ' . $e->getMessage());
        }
    }
}