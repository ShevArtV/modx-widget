<?php

/**
 * @var \modX $modx
 */

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
echo "retry: 5000" . "\n\n";

$basePath = dirname(__FILE__, 4);
$path = $basePath . '/core/services/vendor/autoload.php';
if (!file_exists($path)) {
    echo 'data: {"error":"File `autoload.php` not found"}' . "\n\n";
}

define('MODX_API_MODE', true);
require_once $basePath . '/index.php';
require_once $path;

$modx->getService('error', 'error.modError');
$modx->setLogLevel(\modX::LOG_LEVEL_ERROR);
$qm = new CustomServices\QueueManager($modx);
$token = $_COOKIE['PHPSESSID'] ?? '';

/**
 * @param array $messages
 */
function sendMessages(array $messages): void
{
    global $modx;
    foreach ($messages as $id => $message) {
        echo "data: " . $message . "\n\n";
        echo "id: " . $id . "\n\n";
        ob_flush();
        flush();
    }
}

if ($messages = $qm->getMessages($token, true)) {
    sendMessages($messages);
}
