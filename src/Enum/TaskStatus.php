<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TIMEOUT = 'timeout';
    case FAILED = 'failed';
    case STOPPED = 'stopped';
    case COMPLETED = 'completed';
    case RUNNING = 'running';
    case PENDING = 'pending';
}