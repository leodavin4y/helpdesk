<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin/index');
    }

    public function usersSearch(Request $request)
    {
        $rules = [
            'search' => 'required|string'
        ];
        $errors = $this->validate($request, $rules)->all();

        if (count($errors) > 0) {
            return view('admin/index', [
                'errors' => $errors
            ]);
        }

        $search = $request->input('search');
        $users = DB::table('users')
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->get();

        return view('admin/index', [
            'users' => $users,
            'search' => $search
        ]);
    }

    public function usersDelete(int $id)
    {
        $user = User::find($id);

        if ($user) $user->delete();

        return redirect()->route('admin.index');
    }
}
