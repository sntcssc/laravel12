<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'programme_id'];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}