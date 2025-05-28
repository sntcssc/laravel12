<?php

namespace App\Services;

use App\Repositories\EnrollmentRepository;
use App\Models\Programme;
use App\Models\Enrollment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EnrollmentsExport;
use Barryvdh\DomPDF\Facade\Pdf;

class EnrollmentService
{
    protected $repository;

    public function __construct(EnrollmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($filters, $sort, $direction)
    {
        try {
            return $this->repository->getAll($filters, $sort, $direction);
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve enrollments: ' . $e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            // Check Composite Course restriction
            if (config('app.restrict_composite_course') && $data['programme_id']) {
                $programme = Programme::findOrFail($data['programme_id']);
                if ($programme->name === 'Composite Course') {
                    $exists = Enrollment::where('student_id', $data['student_id'])
                        ->whereHas('programme', fn($q) => $q->where('name', 'Composite Course'))
                        ->exists();
                    if ($exists) {
                        throw new \Exception('Student already enrolled in Composite Course.');
                    }
                }
            }

            // Validate section for Composite Course
            if ($data['programme_id']) {
                $programme = Programme::findOrFail($data['programme_id']);
                if ($programme->name === 'Composite Course' && empty($data['section_id'])) {
                    throw new \Exception('Section is required for Composite Course.');
                } elseif ($programme->name !== 'Composite Course') {
                    $data['section_id'] = null; // Ensure section_id is null for non-Composite Course
                }
            }

            return $this->repository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create enrollment: ' . $e->getMessage());
        }
    }

    public function update($enrollment, array $data)
    {
        try {
            // Similar validation as create
            if (config('app.restrict_composite_course') && $data['programme_id']) {
                $programme = Programme::findOrFail($data['programme_id']);
                if ($programme->name === 'Composite Course') {
                    $exists = Enrollment::where('student_id', $enrollment->student_id)
                        ->whereHas('programme', fn($q) => $q->where('name', 'Composite Course'))
                        ->where('id', '!=', $enrollment->id)
                        ->exists();
                    if ($exists) {
                        throw new \Exception('Student already enrolled in Composite Course.');
                    }
                }
            }

            if ($data['programme_id']) {
                $programme = Programme::findOrFail($data['programme_id']);
                if ($programme->name === 'Composite Course' && empty($data['section_id'])) {
                    throw new \Exception('Section is required for Composite Course.');
                } elseif ($programme->name !== 'Composite Course') {
                    $data['section_id'] = null;
                }
            }

            return $this->repository->update($enrollment, $data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to update enrollment: ' . $e->getMessage());
        }
    }

    public function delete(array $ids)
    {
        try {
            return $this->repository->delete($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete enrollments: ' . $e->getMessage());
        }
    }

    public function restore(array $ids)
    {
        try {
            return $this->repository->restore($ids);
        } catch (\Exception $e) {
            throw new \Exception('Failed to restore enrollments: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {
            return $this->repository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to find enrollment: ' . $e->getMessage());
        }
    }

    public function exportToExcel()
    {
        try {
            return Excel::download(new EnrollmentsExport, 'enrollments.xlsx');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export enrollments to Excel: ' . $e->getMessage());
        }
    }

    public function exportToPDF()
    {
        try {
            $enrollments = $this->repository->getAll([], 'enrolled_at', 'desc')->get();
            $pdf = Pdf::loadView('exports.enrollments', compact('enrollments'));
            return $pdf->download('enrollments.pdf');
        } catch (\Exception $e) {
            throw new \Exception('Failed to export enrollments to PDF: ' . $e->getMessage());
        }
    }
}