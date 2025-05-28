<?php

namespace App\Repositories;

use App\Models\Alumni;
use Illuminate\Support\Facades\DB;

class AlumniRepository
{
    public function getAll($filters = [], $sort = 'completion_date', $direction = 'desc')
    {
        $query = Alumni::query()->with(['student', 'programme', 'batch']);

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
            return Alumni::create($data);
        });
    }

    public function update(Alumni $alumni, array $data)
    {
        return DB::transaction(function () use ($alumni, $data) {
            $alumni->update($data);
            return $alumni;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Alumni::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Alumni::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Alumni::withTrashed()->findOrFail($id);
    }
}