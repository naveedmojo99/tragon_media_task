<?php
namespace App\Http\Controllers\API;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function show(Project $project)
    {
        $this->authorizeUser($project);

        $report = $project->tasks()->with(['remarks' => function ($query) {
            $query->orderBy('remark_date', 'desc');
        }])->get();

        return response()->json([
            'project' => $project->title,
            'project-description'=>$project->description,
            'tasks' => $report
        ]);
    }

    protected function authorizeUser(Project $project)
    {
        abort_if($project->user_id !== auth()->id(), 403, 'Unauthorized');
    }
}
