<?php

namespace App\Jobs;

use App\Models\Statistics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $userId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // create or update statistics for the user

//        $statistics = Statistics::updateOrCreate(
//            ['user_id' => $this->userId],
//            ['task_count' => DB::raw('task_count + 1')]
//        );

        // increment task_count by 1
        $statistic = Statistics::firstOrNew(['user_id' => $this->userId]);
        $statistic->increment('task_count', 1);



    }
}
