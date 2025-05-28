<?php

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function getAll($filters = [], $sort = 'date', $direction = 'desc')
    {
        $query = Attendance::query()->with(['student', 'programme', 'batch']);

        if (!empty($filters['search'])) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('programme', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('batch', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'));
        }

        return $query->orderBy($sort, $direction)->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Attendance::create($data);
        });
    }

    public function update(Attendance $attendance, array $data)
    {
        return DB::transaction(function () use ($attendance, $data) {
            $attendance->update($data);
            return $attendance;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Attendance::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Attendance::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Attendance::findOrFail($id);
    }
}