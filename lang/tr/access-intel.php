<?php

return [
    'app' => [
        'title' => 'Erişim Analizi',
        'brand' => 'Erişim Analizi',
        'nav_aria' => 'Erişim analizi menüsü',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'intel' => 'Skorlar',
        'access_log' => 'Erişim günlüğü',
        'firewall' => 'Güvenlik duvarı',
    ],
    'levels' => [
        'all' => 'Tümü',
        'healthy' => 'Sağlıklı',
        'watch' => 'İzle',
        'risky' => 'Riskli',
        'unknown' => 'Bilinmiyor',
    ],
    'fields' => [
        'ip' => 'IP',
        'score' => 'Skor',
        'level' => 'Seviye',
        'hits' => 'İstek',
        'errors' => 'Hata',
        'blocked' => 'Engellenen',
        'unique_paths' => 'Benzersiz path',
        'last_seen' => 'Son görülme',
        'error_ratio' => 'Hata oranı',
        'blocked_ratio' => 'Engellenme oranı',
        'path_diversity' => 'Path çeşitliliği',
    ],
    'actions' => [
        'refresh' => 'Yenile',
        'view' => 'Detay',
        'block' => 'Blacklist’e ekle',
        'whitelist' => 'Whitelist’e ekle',
        'block_label' => 'Erişim analizinden',
        'whitelist_label' => 'Erişim analizinden',
        'block_reason' => 'Access intel skoruna göre işaretlendi',
        'whitelist_reason' => 'Access intel üzerinden güvenildi',
        'open_log' => 'Erişim günlüğünü aç',
    ],
    'flash' => [
        'blocked' => ':ip blacklist’e eklendi.',
        'whitelisted' => ':ip whitelist’e eklendi.',
    ],
    'confirm' => [
        'block' => 'Bu IP firewall blacklist’e eklensin mi?',
        'whitelist' => 'Bu IP firewall whitelist’e eklensin mi?',
    ],
    'pages' => [
        'index_title' => 'IP sağlık skorları',
        'ip_title' => 'IP :ip',
    ],
    'hint' => [
        'window' => 'Skorlar son :hours saatin erişim günlüğüne göre hesaplanır.',
        'missing_log' => 'Skor için mca/access-log paketini kurun.',
        'suite' => 'Access ailesi: firewall (kurallar) · access-log (geçmiş) · access-intel (skor).',
    ],
    'table' => [
        'empty' => 'Bu pencerede henüz IP trafiği yok.',
    ],
    'errors' => [
        'root_only' => 'Erişim analizini yalnızca root kullanıcılar görebilir.',
        'firewall_missing' => 'mca/firewall yüklü değil.',
    ],
    'modal' => [
        'ok' => 'Tamam',
        'confirm' => 'Onayla',
        'cancel' => 'İptal',
        'close' => 'Kapat',
        'alert_title' => 'Bilgi',
        'confirm_title' => 'Onay',
    ],
    'console' => [
        'install' => [
            'start' => 'MCA Access Intel kuruluyor…',
            'config_ready' => 'Config hazır',
            'assets_published' => 'Asset’ler yayınlandı',
            'need_access_log' => 'Uyarı: mca/access-log yüklü değil — skor ekranı boş kalır.',
            'done' => 'MCA Access Intel kuruldu.',
            'web_ui' => 'Web arayüzü: /:prefix',
        ],
    ],
];
