<?php
namespace App\Http\Controllers\API;

use App\Models\Task;
use App\Models\TaskRemark;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskRemarkController extends Controller
{
    public function index(Task $task)
    {
        $this->authorizeUser($task->project);
        return $task->remarks()->orderBy('remark_date', 'desc')->get();
    }

    public function store(Request $request, Task $task)
    {
        $this->authorizeUser($task->project);

        $request->validate([
            'remark_date' => 'required|date',
            'remark' => 'required|string',
        ]);

        // Either create or update existing remark
        $remark = TaskRemark::updateOrCreate(
            ['task_id' => $task->id, 'remark_date' => $request->remark_date],
            ['remark' => $request->remark]
        );

        return $remark;
    }

    protected function authorizeUser($project)
    {
        abort_if($project->user_id !== auth()->id(), 403, 'Unauthorized');
    }
}
