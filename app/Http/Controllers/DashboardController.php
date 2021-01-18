<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use App\Models\Priority;
use App\Models\Request as Req;
use App\Models\RequestFile;
use App\Models\RequestHistory;
use App\Models\User;
use App\Models\RequestStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;

class DashboardController extends Controller
{
    /**
     * @var RequestRepositoryInterface
     */
    private $requestRepository;

    /**
     * DashboardController constructor.
     * @param RequestRepositoryInterface $requestRepository
     */
    public function __construct(RequestRepositoryInterface $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function index(): RedirectResponse
    {
        $role = Auth::user()->role;

        if ($role === User::ROLE_USER) {
            return redirect()->route('dashboard.user');
        } elseif ($role === User::ROLE_WORKER) {
            return redirect()->route('dashboard.worker');
        } elseif ($role === User::ROLE_ADMIN) {
            return redirect()->route('dashboard.admin');
        }

        throw new \Exception('Unknown user role: ' . $role);
    }

    /**
     * Возвращает данные для модального окна создания новой заявки
     *
     * @return array
     */
    private function getRequestFormData(): array
    {
        return [
            'categories' => [
                'parent' => Category::whereNull('parent_id')->get(),
                'sub' => Category::whereNotNull('parent_id')->get()
            ],
            'priorities' => Priority::all(),
        ];
    }

    public function userBoard(Request $request): View
    {
        if (!is_null($request->status)) session(['status_id' => $request->status]);

        $user = Auth::user();
        $status = session('status_id', 1);
        $activeIds = [
            RequestStatus::NEW,
            RequestStatus::WORKER_ASSIGNED,
            RequestStatus::IN_PROGRESS,
            RequestStatus::AWAIT_APPROVE
        ];
        $inactiveIds = [RequestStatus::SOLVED, RequestStatus::CLOSED];
        $statuses = $status ? $activeIds : $inactiveIds;
        $counter = Req::whereIn('status_id', !$status ? $activeIds : $inactiveIds)
            ->where('user_id', '=', $user->id)
            ->count();
        $requests = Req::whereIn('status_id', $statuses)
            ->where('user_id', '=', $user->id)
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('dashboard/index', [
            'request' => $this->getRequestFormData(),
            'requests' => $requests,
            'request_statuses' => [
                'statuses' => RequestStatus::all(),
                'selected' => $status
            ],
            'tabs' => [
                ['id' => 1, 'name' => 'Активные заявки'],
                ['id' => 0, 'name' => 'Завершенные'],
            ],
            'active_tab' => $status,
            'counter' => $counter
        ]);
    }

    public function workerBoard(Request $request): View
    {
        if (!is_null($request->status)) session(['status_id' => $request->status]);

        $user = Auth::user();
        $status = session('status_id', 1);
        $requests = Req::where('status_id', '=', $status)
            ->where('worker_id', '=', $user->id)
            ->with('user')
            ->paginate();

        return view('dashboard/index', [
            'user' => $user,
            'request' => $this->getRequestFormData(),
            'requests' => $requests,
            'request_statuses' => [
                'statuses' => RequestStatus::all(),
                'selected' => $status
            ],
            'tabs' => [
                ['id' => RequestStatus::WORKER_ASSIGNED, 'name' => 'К исполнению'],
                ['id' => RequestStatus::IN_PROGRESS, 'name' => 'В работе'],
                ['id' => RequestStatus::AWAIT_APPROVE, 'name' => 'Ожидают проверки'],
                ['id' => RequestStatus::SOLVED, 'name' => 'Решенные'],
                ['id' => RequestStatus::CLOSED, 'name' => 'Закрытые'],
            ],
            'active_tab' => $status
        ]);
    }

    public function adminBoard(Request $request)
    {
        $this->validate($request, ['status' => 'nullable|integer|exists:App\Models\RequestStatus,id']);

        if (!is_null($request->status)) session(['status_id' => $request->status]);

        $status = session('status_id', 1);
        $requests = Req::where('status_id', '=', $status)
            ->with('user')
            ->orderBy('priority_id', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('dashboard/index', [
            'request' => $this->getRequestFormData(),
            'requests' => $requests,
            'request_statuses' => [
                'statuses' => RequestStatus::all(),
                'selected' => $status
            ],
        ]);
    }

    public function storeRequest(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|string|min:1|max:10',
            'subcategory_id' => 'nullable|string|min:1|max:10',
            'priority_id' => 'required|string|min:1|max:10',
            'title' => 'required|string|min:2|max:255',
            'description' => 'required|string|min:2|max:60000',
            'files' => 'nullable|array'
        ]);

        try {
            $user = Auth::user();
            $subCatId = $request->input('subcategory_id');

            DB::transaction(function() use(&$user, &$subCatId, &$request) {
                $req = new Req();

                $req->category_id = $subCatId ? $subCatId : $request->input('category_id');
                $req->priority_id = $request->input('priority_id');
                $req->user_id = $user->id;
                $req->title = $request->input('title');
                $req->description = $request->input('description');
                $req->status_id = RequestStatus::NEW;

                if (!$req->save()) throw new \Exception('Failed to store request');

                $files = $request->file('files');

                if ($files && count($files) > 0) {
                    foreach ($files as $index => $file) {
                        $path = $file->store('files');

                        $requestFile = new RequestFile();
                        $requestFile->request_id = $req->id;
                        $requestFile->type = $file->extension();
                        $requestFile->original_name = $file->getClientOriginalName();
                        $requestFile->name = basename($path);

                        if (!$requestFile->save()) throw new \Exception('Failed to store file');
                    }
                }
            });

            return back()->with('success', 'Успешно сохранено');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
            'status_id' => 'required|integer|exists:App\Models\RequestStatus,id',
            'worker_id' => 'nullable|integer|exists:App\Models\User,id'
        ]);

        try {
            $workerId = $request->worker_id ?? null;
            $statusId = !is_null($workerId) ? RequestStatus::WORKER_ASSIGNED : $request->status_id;
            $this->requestRepository->updateWithHistory($id, [
                'status_id' => $statusId,
                'worker_id' => $workerId
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Статус заявки изменен');
    }

    public function workerStart(int $id)
    {
        try {
            $this->requestRepository->updateWithHistory($id, ['status_id' => RequestStatus::IN_PROGRESS]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Статус заявки изменен');
    }

    public function workerDone(int $id)
    {
        try {
            $this->requestRepository->updateWithHistory($id, ['status_id' => RequestStatus::AWAIT_APPROVE]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Статус заявки изменен');
    }

    public function initiatorSolved(int $id)
    {
        try {
            $this->requestRepository->updateWithHistory($id, ['status_id' => RequestStatus::SOLVED]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Статус заявки изменен');
    }

    public function initiatorRepeat(int $id)
    {
        try {
            $this->requestRepository->updateWithHistory($id, ['status_id' => RequestStatus::IN_PROGRESS]);
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

    public function show(int $id)
    {
        $req = Req::find($id);

        if (!$req) return back()->with('error', "Заявка #{$id} не существует");

        $messages = Message::where('request_id', '=', $id)
            ->orderBy('id', 'DESC')
            ->paginate(30);

        $notifications = RequestHistory::where('request_id', '=', $id)
            ->orderBy('id', 'DESC')
            ->get();

        return view('dashboard/request', [
            'request' => $req,
            'messages' => $messages,
            'notifications' => $notifications
        ]);
    }

    public function messageNew(Request $request, int $id, Message $msg)
    {
        $this->validate($request, [
            'text' => 'required|string|min:1'
        ]);
        $user = Auth::user();
        $req = Req::find($id);

        if (!$req) return back()->with('error', 'Заявка не существует');
        if ($req->user_id !== $user->id && $req->worker_id !== $user->id)
            return back()->with('error', 'Не хватает прав');

        try {
            $msg->request_id = $id;
            $msg->user_id = $user->id;
            $msg->text = $request->text;

            if (!$msg->save()) throw new \Exception('Failed to store message');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Сообщение успешно сохранено');
    }

    public function messageDelete(int $id)
    {
        $user = Auth::user();
        $msg = Message::find($id);

        if (!$msg) return back()->with('error', 'Сообщение не найдено');
        if ($msg->user->id !== $user->id) abort(403);

        $msg->delete();

        return back()->with('success', 'Успешно удалено');
    }
}
