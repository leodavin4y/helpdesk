<?php

namespace App\Http\Controllers;

use App\Repositories\RequestRepository;
use App\Charts\RequestChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RequestChartController extends Controller
{
    private $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    private function monthToRus($str)
    {
        $ruMonths = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return str_replace($enMonths, $ruMonths, $str);
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'period' => 'nullable|integer|min:0|max:2',
            'type' => 'nullable|string|in:line,bar'
        ]);

        $type = $request->type;

        if ($type) {
            session()->put('type', $type);
        }

        $type = session('type', 'line');
        $period = (int) $request->input('period', 0);
        $labels = [];
        $values = [];

        if ($period === 0) {
            $datasetName = 'Заявок в неделю';
            $reportData = $this->requestRepository->reportCountLastWeek();

            foreach ($reportData as $item) {
                $labels[] = $item->p;
                $values[] = $item->counter;
            }
        } elseif($period === 1) {
            $datasetName = 'Заявок по месяцам';
            $reportData = $this->requestRepository->reportCountByMonth();

            foreach ($reportData as $item) {
                $labels[] = $this->monthToRus(\DateTime::createFromFormat('!m', $item->p)->format('F'));
                $values[] = $item->counter;
            }
        } else {
            $datasetName = 'Заявок по годам';
            $reportData = $this->requestRepository->reportCountByYear();

            foreach ($reportData as $item) {
                $labels[] = $item->p;
                $values[] = $item->counter;
            }
        }

        $requestChart = new RequestChart;
        $requestChart->labels($labels);
        $requestChart->dataset($datasetName, $type, $values);
        $requestChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales'              => [
                'xAxes' => [],
                'yAxes' => [
                    [
                        'ticks' => [
                            'beginAtZero' => true,
                            'stepSize' => 1
                        ],
                    ],
                ],
            ],
        ], true);

        return view('admin/requests', [
            'periods' => [
                ['id' => 0, 'name' => 'За неделю'],
                ['id' => 1, 'name' => 'По месяцам'],
                ['id' => 2, 'name' => 'По годам']
            ],
            'selected_period' => $period,
            'types' => [
                'line' => 'Линия',
                'bar' => 'Гистограмма'
            ],
            'selected_type' => $type,
            'requestChart' => $requestChart,
        ]);
    }
}
