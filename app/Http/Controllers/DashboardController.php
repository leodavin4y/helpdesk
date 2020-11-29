<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
            ]
        ]);
    }
}
