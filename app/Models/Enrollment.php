<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'programme_id', 'batch_id', 'section_id', 'enrolled_at'];

    protected $dates = ['enrolled_at'];

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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}