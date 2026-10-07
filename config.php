<?php
return [
 // Default outside DOCUMENT_ROOT; change only to another non-public writable directory.
 'storage_dir' => dirname($_SERVER['DOCUMENT_ROOT'] ?? __DIR__) . '/omp-private',
 // Local PHP mail transport; delivery depends on the hosting mail configuration.
 'notification_email' => 'prece@netlifly.space',
 'notification_from' => 'prece@netlifly.space',
 'retention_days' => 365,
 'privacy_version' => '2026-10-06-v1',
];
