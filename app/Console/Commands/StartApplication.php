<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class StartApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing dependencies...');
        $this->runProcess(['composer', 'install']);

        if (!File::exists(base_path('.env'))) {
            $this->info('Copying .env.example to .env...');
            File::copy(base_path('.env.example'), base_path('.env'));
        }

        $this->info('Installing npm dependencies...');
        $this->runProcess(['npm', 'install']);

        $this->info('Building npm assets...');
        $this->runProcess(['npm', 'run', 'build']);

        $this->info('Clearing cache...');
        $this->runProcess(['php', 'artisan', 'optimize:clear']);

        $this->info('Generating application key...');
        $this->runProcess(['php', 'artisan', 'key:generate']);

        $this->info('Caching configuration...');
        $this->runProcess(['php', 'artisan', 'config:cache']);

        $this->info('Creating database...');
        $this->runProcess(['php', 'artisan', 'db:create', 'task_management_system']);

        $this->info('Running migrations and seeders...');
        $this->runProcess(['php', 'artisan', 'migrate:fresh', '--seed']);

        $this->info('Starting the application...');
        $this->runProcess(['php', 'artisan', 'serve']);
    }
    private function runProcess(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info($process->getOutput());
    }
}
