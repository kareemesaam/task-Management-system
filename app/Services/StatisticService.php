<?php

namespace App\Services;

use App\Models\Statistics;
use App\Services\BaseService;

class StatisticService extends BaseService
{
    public function __construct(Statistics $model)
    {
        parent::__construct($model);
    }

    public function topStatistics(int $number)
    {
        return $this->withRelations(['user:id,name'])->orderByDesc('task_count')
            ->limit($number)
            ->get();
    }
}
