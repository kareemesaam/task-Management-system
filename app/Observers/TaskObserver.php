<?php

namespace App\Observers;

use App\Jobs\UpdateStatisticsJob;
use App\Models\Task;

class TaskObserver
{
    public function created(Task $task)
    {
        UpdateStatisticsJob::dispatch($task->assigned_by_id);
    }
}
