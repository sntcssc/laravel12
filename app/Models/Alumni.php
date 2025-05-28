<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Alumni extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'programme_id', 'batch_id', 'completion_date'];

    protected $dates = ['completion_date'];

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