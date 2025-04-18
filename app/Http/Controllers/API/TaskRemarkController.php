<?php
namespace App\Http\Controllers\API;

use App\Models\Task;
use App\Models\TaskRemark;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
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

    // Validate only the remark field
    $request->validate([
        'remark' => 'required|string',
    ]);

    $today = Carbon::today()->toDateString(); // Get current date (Y-m-d)

    // Create or update today's remark
    $remark = TaskRemark::updateOrCreate(
        ['task_id' => $task->id, 'remark_date' => $today],
        ['remark' => $request->remark]
    );

    return response()->json([
        'message' => 'Remark saved successfully.',
        'data' => $remark,
    ], 200);
    }

    protected function authorizeUser($project)
    {
        abort_if($project->user_id !== auth()->id(), 403, 'Unauthorized');
    }
}
