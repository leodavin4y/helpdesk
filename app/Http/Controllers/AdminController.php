<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin/index');
    }

    public function usersSearch(Request $request)
    {
        $this->validate($request, [
            'search' => 'required|string'
        ]);

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
        $errors = $this->validate($request, [
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|max:1|min:1'
        ]);
        $roleId = intval($request->input('role'));

        if (!in_array($roleId, [1, 2, 3])) abort(422);

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
