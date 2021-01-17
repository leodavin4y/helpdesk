<?php

namespace App\Http\Controllers;

use App\Repositories\RequestRepository;
use App\Charts\RequestChart;
use Illuminate\Http\Request;

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
            'period' => 'nullable|integer|min:0|max:1'
        ]);

        $period = (int) $request->input('period', 0);
        $labels = [];
        $values = [];

        if ($period === 0) {
            $reportData = $this->requestRepository->reportCountByMonth();

            foreach ($reportData as $item) {
                $labels[] = $this->monthToRus(\DateTime::createFromFormat('!m', $item->p)->format('F'));
                $values[] = $item->counter;
            }
        } else {
            $reportData = $this->requestRepository->reportCountByYear();

            foreach ($reportData as $item) {
                $labels[] = $item->p;
                $values[] = $item->counter;
            }
        }

        $requestChart = new RequestChart;
        $requestChart->labels($labels);
        $requestChart->dataset($period === 0 ? 'Заявок в месяц' : 'Заявок в год', 'line', $values);

        return view('admin/requests', [
            'periods' => [
                ['id' => 0, 'name' => 'По месяцам'],
                ['id' => 1, 'name' => 'По годам']
            ],
            'selected_period' => $period,
            'requestChart' => $requestChart,
        ]);
    }
}
