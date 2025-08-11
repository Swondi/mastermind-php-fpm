<?php

namespace App\Config;

final class NotificationPreferencesConfig
{
    public const ALL_PREFERENCES = [
        'server_overload_alerts' => ['email' => true, 'mobile' => false],
        'node_offline_warnings' => ['email' => true, 'mobile' => true],
        'high_latency_alerts' => ['email' => true, 'mobile' => false],
        'security_breach_detected' => ['email' => true, 'mobile' => false],        
    ];
}
