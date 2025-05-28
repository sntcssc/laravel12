<?php

namespace App\Repositories;

use App\Models\Leave;
use Illuminate\Support\Facades\DB;

class LeaveRepository
{
    public function getAll($filters = [], $sort = 'start_date', $direction = 'desc')
    {
        $query = Leave::query()->with(['student', 'programme', 'batch']);

        if (!empty($filters['search'])) {
            $query->whereHas('student', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('programme', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhereHas('batch', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'))
                  ->orWhere('reason', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Leave::create($data);
        });
    }

    public function update(Leave $leave, array $data)
    {
        return DB::transaction(function () use ($leave, $data) {
            $leave->update($data);
            return $leave;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Leave::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Leave::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Leave::withTrashed()->findOrFail($id);
    }
}