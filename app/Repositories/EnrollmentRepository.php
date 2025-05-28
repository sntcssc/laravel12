<?php

namespace App\Repositories;

use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class EnrollmentRepository
{
    public function getAll($filters = [], $sort = 'enrolled_at', $direction = 'desc')
    {
        $query = Enrollment::query()->with(['student', 'programme', 'batch', 'section']);

        if (!empty($filters['search'])) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('programme', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('batch', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'));
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Enrollment::create($data);
        });
    }

    public function update(Enrollment $enrollment, array $data)
    {
        return DB::transaction(function () use ($enrollment, $data) {
            $enrollment->update($data);
            return $enrollment;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Enrollment::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Enrollment::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Enrollment::withTrashed()->findOrFail($id);
    }
}