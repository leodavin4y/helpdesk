<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Request as Req;
use App\Models\User;
use App\Models\RequestStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
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
            'active_requests' => Req::where('status_id', '=', $request->post('status', 1))->get(),
            'active_requests_count' => Req::count(),
            'request_statuses' => [
                'statuses' => RequestStatus::all(),
                'selected' => $request->post('status', 1)
            ],
            'workers' => User::where('role', '=', 2)->get()
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
        $req->status_id = 1;

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

    public function updateStatus(Request $request, int $id)
    {
        $this->validate($request, [
            'status' => 'required|integer|exists:App\Models\RequestStatus,id',
            'worker' => 'nullable|integer|exists:App\Models\User,id'
        ]);

        try {
            $req = Req::find($id);

            if (!$req) throw new \Exception('Заявка не существует');

            $req->status_id = $request->input('status');
            $req->worker_id = $request->input('worker', null);

            if (!$req->save()) throw new \Exception('Не удалось сохранить');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Статус заявки изменен');
    }

    public function getUsers(int $role)
    {
        return response()->json([
            'users' => User::where('role', '=', $role)->get()
        ]);
    }
}
