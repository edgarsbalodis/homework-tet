<?php

namespace Backscreen\Helpers;

use Illuminate\Support\Facades\Log;

class Logger
{
    public function log($level, $message, array $context = [])
    {
        // Custom log format
        $timestamp = now()->format('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] [{$level}] {$message}";

        // Log the message
        Log::log($level, $formattedMessage, $context);
    }
}