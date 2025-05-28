<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository
{
    public function getAll($filters = [], $sort = 'name', $direction = 'asc')
    {
        $query = Student::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy($sort, $direction)->withTrashed()->paginate(10);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Student::create($data);
        });
    }

    public function update(Student $student, array $data)
    {
        return DB::transaction(function () use ($student, $data) {
            $student->update($data);
            return $student;
        });
    }

    public function delete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Student::whereIn('id', $ids)->delete();
        });
    }

    public function restore(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Student::withTrashed()->whereIn('id', $ids)->restore();
        });
    }

    public function find($id)
    {
        return Student::withTrashed()->findOrFail($id);
    }
}