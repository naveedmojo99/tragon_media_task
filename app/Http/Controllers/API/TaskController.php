<?php
namespace App\Http\Controllers\API;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $this->authorizeUser($project);
        return $project->tasks;
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeUser($project);
        $request->validate([
            'title' => 'required|string',
            'priority' => 'in:Low,Medium,High',
        ]);

        return $project->tasks()->create([
            'title' => $request->title,
            'priority' => $request->priority ?? 'Medium',
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeUser($task->project);
        $request->validate([
            'title' => 'nullable|string',
            'status' => 'in:Pending,In Progress,Completed',
            'priority' => 'in:Low,Medium,High',
        ]);

        $task->update($request->only('title', 'status', 'priority'));
        return $task;
    }

    public function destroy(Task $task)
    {
        $this->authorizeUser($task->project);
        $task->delete();
        return response()->noContent();
    }

    protected function authorizeUser(Project $project)
    {
        abort_if($project->user_id !== auth()->id(), 403, 'Unauthorized');
    }
}
