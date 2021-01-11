<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $this->validate($request, [
            'name' => 'required|string|min:1|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|numeric|digits_between:1,3',
            'password' => 'nullable|string|min:6|max:20'
        ]);

        $user = User::find($userId);
        if (!$user) abort(404);

        $user->role = intval($request->role);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

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
