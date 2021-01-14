<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Faq;

class FaqController extends Controller {

    /**
     * Список заметок с разбивкой на страницы
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $results = Faq::paginate();

        return view('faq/index', [
            'results' => $results
        ]);
    }

    /**
     * Поиск по заметкам с разбивкой результата на страницы
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'search' => 'required|string|min:1|max:30'
        ]);
        $query = $request->search;
        $results = Faq::search($query)->paginate();

        return view('faq/index', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Просмотр заметки по ид
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function view(int $id)
    {
        $faq = FAQ::find($id);

        if (!$faq) return back()->with('error', "Заметка #{$id} не существует");

        return view('faq/view', [
            'faq' => $faq
        ]);
    }

    /**
     * Новая заметка
     *
     * @return View
     */
    public function store(): View
    {
        return view('faq/store');
    }

    /**
     * Редактировать заметку по ид
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, int $id)
    {
        $faq = FAQ::find($id);

        if (!$faq) return back()->with('error', "Заметка #{$id} не существует");
        if ($request->method() === 'POST') return $this->storeFAQ($request, $faq);

        return view('faq/store', [
            'faq' => $faq
        ]);
    }

    /**
     * Сохранить заметку
     *
     * @param Request $request
     * @param Faq|null $faq
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeFAQ(Request $request, ?Faq $faq = null): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required|string|min:2|max:255',
            'text' => 'required|string|min:10|max:65535',
        ], [
            'title.required' => 'Поле "Заголовок" является обязательным',
            'text.required' => 'Поле "Текст" является обязательным',
            'title.min' => 'Минимальная длина заголовка - :min символа',
            'title.max' => 'Максимальная длина заголовка - :max символов',
            'text.min' => 'Минимальная длина текста - :min символов',
            'text.max' => 'Максимальная длина текста - :max символа',
        ]);

        try {
            if (is_null($faq)) $faq = new FAQ();

            $onlyText = html_entity_decode(strip_tags($request->text), ENT_QUOTES);
            $faq->title = $request->title;
            $faq->text = $request->text;
            $faq->annotation = mb_substr($onlyText, 0, 512, 'UTF-8');

            if (!$faq->save()) throw new \Exception('Failed to store FAQ');

            $link = route('faq.view', [$faq->id]);

            return back()->with('success', "<a href='{$link}'>Заметка</a> успешно сохранена");
        } catch (\Exception $e) {
            return back()->with('error', 'Не удалось сохранить');
        }
    }

    /**
     * Удалить заметку по ид
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        try {
            $faq = FAQ::find($id);

            if (!$faq) return back()->with('error', "Заметка #{$id} не существует");

            $faq->delete();
        } catch (\Exception $e) {
            return back()->with('error', "Ошибка при удалении заметки #{$id}");
        }

        return back()->with('success', "Заметка #{$id} успешно удалена");
    }
}
