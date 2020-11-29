<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        $users = $users->map(function($user) {
            return (new User())->fill((array) $user);
        });

        return view('admin/index', [
            'users' => $users,
            'search' => $search
        ]);
    }

    public function usersEdit(Request $request, int $userId)
    {
        $rules = [
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:1|min:1'
        ];
        $errors = $this->validate($request, $rules)->all();
        $roleId = intval($request->input('role'));

        if (count($errors) > 0 || $roleId <= 0 || $roleId >= 4) abort(422);

        $user = User::find($userId);

        if (!$user) abort(404);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $roleId;

        return new JsonResponse([
            'status' =>  $user->save()
        ]);
    }

    public function usersDelete(int $id)
    {
        $user = User::find($id);

        if (!$user) abort(404);

        return new JsonResponse([
            'status' => $user->delete()
        ]);
    }
}
