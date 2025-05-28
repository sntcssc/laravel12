<?php

namespace App\Repositories;

use App\Models\Programme;
use Illuminate\Support\Facades\DB;

class ProgrammeRepository
{
    public function getAll($filters = [], $sort = 'name', $direction = 'asc')
    {
        $query = Programme::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Programme::create($data);
        });
    }

    public function update(Programme $programme, array $data)
    {
        return DB::transaction(function () use ($programme, $data) {
            $programme->update($data);
            return $programme;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Programme::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Programme::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Programme::withTrashed()->findOrFail($id);
    }
}