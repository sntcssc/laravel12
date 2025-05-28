<?php

namespace App\Repositories;

use App\Models\Batch;
use Illuminate\Support\Facades\DB;

class BatchRepository
{
    public function getAll($filters = [], $sort = 'year', $direction = 'asc')
    {
        $query = Batch::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('year', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Batch::create($data);
        });
    }

    public function update(Batch $batch, array $data)
    {
        return DB::transaction(function () use ($batch, $data) {
            $batch->update($data);
            return $batch;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Batch::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Batch::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Batch::withTrashed()->findOrFail($id);
    }
}