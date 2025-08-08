<?php

namespace App\Enum;

enum NodeStatus: string
{
    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case DEGREDED = 'degreded';
    case ERRORED = 'errored';
    case UNKNOWN = 'unknown';
}