<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Request as Req;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard/index', [
            'user' => $user,
            'request' => [
                'categories' => [
                    'parent' => Category::whereNull('parent_id')->get(),
                    'sub' => Category::whereNotNull('parent_id')->get()
                ],
                'priorities' => Priority::all(),
                'projects' => Project::all()
            ],
            'active_requests' => Req::all(),
            'active_requests_count' => Req::count()
        ]);
    }

    public function storeRequest(Request $request)
    {
        $rules = [
            'category_id' => 'required|string|min:1|max:10',
            'subcategory_id' => 'nullable|string|min:1|max:10',
            'priority_id' => 'required|string|min:1|max:10',
            'project_id' => 'required|string|min:1|max:10',
            'title' => 'required|string|min:2|max:255',
            'description' => 'required|string|min:2|max:60000'
        ];
        $errors = $this->validate($request, $rules)->all();

        if (count($errors) > 0) abort(422);

        $subCatId = $request->input('subcategory_id');

        $req = new Req();

        $req->category_id = $subCatId ? $subCatId : $request->input('category_id');
        $req->priority_id = $request->input('priority_id');
        $req->project_id = $request->input('project_id');
        $req->title = $request->input('title');
        $req->description = $request->input('description');

        $status = $req->save();

        return redirect('dashboard')->with('status', $status);
    }

    public function deleteRequest(int $id)
    {
        $request = Req::find($id);

        if (!$request) return abort(404);

        $status = $request->delete();

        return new JsonResponse([
            'status' => $status
        ]);
    }
}
