<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRemark extends Model
{

    protected $fillable = [
        'task_id',
        'remark_date',
        'remark',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
