<?php

return [
    'app' => [
        'title' => 'Access Intel',
        'brand' => 'Access Intel',
        'nav_aria' => 'Access intel navigation',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'intel' => 'Scores',
        'access_log' => 'Access log',
        'firewall' => 'Firewall',
    ],
    'levels' => [
        'all' => 'All',
        'healthy' => 'Healthy',
        'watch' => 'Watch',
        'risky' => 'Risky',
        'unknown' => 'Unknown',
    ],
    'fields' => [
        'ip' => 'IP',
        'score' => 'Score',
        'level' => 'Level',
        'hits' => 'Hits',
        'errors' => 'Errors',
        'blocked' => 'Blocked',
        'unique_paths' => 'Unique paths',
        'last_seen' => 'Last seen',
        'error_ratio' => 'Error rate',
        'blocked_ratio' => 'Block rate',
        'path_diversity' => 'Path diversity',
    ],
    'actions' => [
        'refresh' => 'Refresh',
        'view' => 'Details',
        'block' => 'Blacklist IP',
        'whitelist' => 'Whitelist IP',
        'block_label' => 'From access intel',
        'whitelist_label' => 'From access intel',
        'block_reason' => 'Flagged by access intel scoring',
        'whitelist_reason' => 'Trusted via access intel',
        'open_log' => 'Open access log',
    ],
    'flash' => [
        'blocked' => ':ip added to blacklist.',
        'whitelisted' => ':ip added to whitelist.',
    ],
    'confirm' => [
        'block' => 'Blacklist this IP in the firewall?',
        'whitelist' => 'Whitelist this IP in the firewall?',
    ],
    'pages' => [
        'index_title' => 'IP health scores',
        'ip_title' => 'IP :ip',
    ],
    'hint' => [
        'window' => 'Scores use the last :hours hours of access logs.',
        'missing_log' => 'Install mca/access-log to enable scoring.',
        'suite' => 'Access suite: firewall (rules) · access-log (history) · access-intel (scores).',
    ],
    'table' => [
        'empty' => 'No IP traffic in this window yet.',
    ],
    'errors' => [
        'root_only' => 'Only root users can view access intel.',
        'firewall_missing' => 'mca/firewall is not installed.',
    ],
    'modal' => [
        'ok' => 'OK',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'alert_title' => 'Notice',
        'confirm_title' => 'Confirm',
    ],
    'console' => [
        'install' => [
            'start' => 'Installing MCA Access Intel…',
            'config_ready' => 'Config ready',
            'assets_published' => 'Assets published',
            'need_access_log' => 'Warning: mca/access-log is not installed — scoring UI will be empty.',
            'done' => 'MCA Access Intel installed.',
            'web_ui' => 'Web UI: /:prefix',
        ],
    ],
];
