<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRemark extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
