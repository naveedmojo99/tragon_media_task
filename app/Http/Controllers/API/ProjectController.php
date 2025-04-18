<?php
namespace App\Http\Controllers\API;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
           
        ]);
    }

    public function show(Project $project)
    {
        $this->authorizeUser($project);
        return $project;
    }

    public function update(Request $request, Project $project)
    {
        // Authorize the user (if required)
        $this->authorizeUser($project);
    
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Update the project with validated data
        $project->update($validatedData);
    
        // Return updated project with 200 OK
        return response()->json([
            'message' => 'Project updated successfully.',
            'data' => $project
        ], 200);
    }
    

    public function destroy(Project $project)
    {
        $this->authorizeUser($project);
        $project->delete();
        return response()->json([
            'message' => 'Project deleted succesfully',
        ], 200);
    }

    protected function authorizeUser(Project $project)
    {
        abort_if($project->user_id !== Auth::id(), 403, 'Unauthorized');
    }
}
