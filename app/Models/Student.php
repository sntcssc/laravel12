<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function alumni()
    {
        return $this->hasMany(Alumni::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}