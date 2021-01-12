<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\User;
use App\Models\Request as Req;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Главная страница админ-панели
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin/index', [
            'users' => User::paginate(),
            'users_total' => User::count(),
            'workers_total' => User::where('role', '=', 2)->count(),
            'roles' => [
                1 => 'Инициатор',
                2 => 'Исполнитель',
                3 => 'Администратор'
            ],
        ]);
    }

    /**
     * Страница статистики по конкретному пользователю
     *
     * @param Request $request
     * @param int $id - ид пользователя
     * @return View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function user(Request $request, int $id): View
    {
        $user = User::find($id);
        if (!$user) abort(404);

        $periods = [
            'week' => '7 DAY',
            'month' => '1 MONTH',
            'year' => '1 YEAR'
        ];
        $this->validate($request, [
            'period' => ['nullable', 'string', Rule::in(array_keys($periods))]
        ]);

        $interval = $request->period ? $periods[$request->period] : $periods['week'];

        if ($user->role === 2) {
            $requestsByPeriod = Req::join('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
                ->where('requests.worker_id', '=', $id)
                ->where('requests.status_id', '=', 5)
                ->where('requests.created_at', '>=', DB::raw('DATE_SUB(CURRENT_DATE, INTERVAL ' . $interval . ')'))
                ->paginate();
            $activeRequests = Req::join('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
                ->where('requests.worker_id', '=', $id)
                ->whereIn('requests.status_id', [2, 3, 4])
                ->count();
        } elseif($user->role === 1) {
            $requestsByPeriod = Req::join('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
                ->where('requests.user_id', '=', $user->id)
                ->where('requests.created_at', '>=', DB::raw('DATE_SUB(CURRENT_DATE, INTERVAL ' . $interval . ')'))
                ->paginate();
            $activeRequests = Req::join('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
                ->where('requests.user_id', '=', $user->id)
                ->whereIn('requests.status_id', [2, 3, 4])
                ->count();
        } else {
            $requestsByPeriod = 0;
            $activeRequests = 0;
        }

        return view('admin/user', [
            'user' => $user,
            'periods' => [
                'week' => 'Последние 7 дней',
                'month' => 'Последний месяц',
                'year' => 'Последний год'
            ],
            'requests' => $requestsByPeriod,
            'active_requests' => $activeRequests
        ]);
    }

    /**
     * Поиск пользователей
     *
     * @param Request $request
     * @return View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function usersSearch(Request $request): View
    {
        $this->validate($request, [
            'search' => 'required|string',
            'role.*' => 'nullable|numeric|digits_between:1,3'
        ]);

        $roles = [1, 2, 3];
        if ($request->role) $roles = array_values($request->role);

        $search = $request->input('search');
        $users = DB::table('users')
            ->whereIn('role', $roles)
            ->where(function($query) use($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->paginate();

        // items as model (default array)
        $users->getCollection()->transform(function ($user) {
            return (new User())->fill((array) $user);
        });

        return view('admin/index', [
            'users' => $users,
            'users_total' => User::count(),
            'workers_total' => User::where('role', '=', 2)->count(),
            'search' => $search,
            'roles' => [
                1 => 'Инициатор',
                2 => 'Исполнитель',
                3 => 'Администратор'
            ],
        ]);
    }

    /**
     * Изменить профиль пользователя
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Удалить профиль пользователя
     *
     * @param int $id
     * @return JsonResponse
     */
    public function usersDelete(int $id)
    {
        $user = User::find($id);

        if (!$user) abort(404);

        return new JsonResponse([
            'status' => $user->delete()
        ]);
    }
}
