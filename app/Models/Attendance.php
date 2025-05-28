<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id', 'programme_id', 'batch_id', 'date', 'status'];

    protected $dates = ['date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}