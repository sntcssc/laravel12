<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'programme_id', 'batch_id', 'start_date', 'end_date', 'reason', 'status'];

    protected $dates = ['start_date', 'end_date'];

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