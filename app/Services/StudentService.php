<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentService
{
    protected $repository;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve students: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create student: ' . $e->getMessage());
        }
    }

    public function update($student, array $data)
    {
        try {
            return $this->repository->update($student, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update student: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete students: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore students: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find student: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new StudentsExport, 'students.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export students to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $students = $this->repository->getAll([], 'name', 'asc')->get();
            $pdf = Pdf::loadView('exports.students', compact('students'));
            return $pdf->download('students.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export students to PDF: ' . $e->getMessage());
        }
    }
}