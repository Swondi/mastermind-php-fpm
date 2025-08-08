<?php

namespace App\Enum;

enum TaskType: string
{
    case SYNCFILE = 'rsync';
    case CMD = 'cmd';
}