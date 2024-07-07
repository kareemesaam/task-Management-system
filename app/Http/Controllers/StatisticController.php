<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\IStatisticRepository;
use App\Services\StatisticService;

class StatisticController extends Controller
{
    public function index(StatisticService $statisticService)
    {
//        $statistics = $statisticService->withRelations(['user:id,name'])->topStatistics(10);

        $statistics = $statisticService->topStatistics(10);
        return view('statistics.index', compact('statistics'));
    }
}
