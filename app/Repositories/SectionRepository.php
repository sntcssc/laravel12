<?php

namespace App\Repositories;

use App\Models\Section;
use Illuminate\Support\Facades\DB;

class SectionRepository
{
    public function getAll($filters = [], $sort = 'name', $direction = 'asc')
    {
        $query = Section::query()->with('programme');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('programme', fn($q) => $q->where('name', 'like', '%' . $filters['search'] . '%'));
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Section::create($data);
        });
    }

    public function update(Section $section, array $data)
    {
        return DB::transaction(function () use ($section, $data) {
            $section->update($data);
            return $section;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Section::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Section::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Section::withTrashed()->findOrFail($id);
    }
}