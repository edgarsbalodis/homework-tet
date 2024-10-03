<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;


class ClearCache implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $command = 'php artisan cache:clear';

        Log::info("Executing cache clear command: {$command}");

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            Log::error('Error clearing cache: ' . implode("\n", $output));
        } else {
            Log::info("Cache cleared successfully.");
        }
    }
}
