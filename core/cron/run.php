<?php

use CustomServices\QueueManager;

define('MODX_API_MODE', true);
require_once dirname(__FILE__, 3) . '/index.php';
require_once MODX_BASE_PATH . 'core/services/vendor/autoload.php';
$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
switch ($argv[1]) {
    case 'read_queue':
        // php -d display_errors -d error_reporting=E_ALL dev.ipkprofit.ru/public_html/core/cron/run.php read_queue
        $QM = new QueueManager($modx);
        if ($messages = $QM->getMessages('CustomServices')) {
            $oldUser = $modx->user;
            foreach ($messages as $message) {
                $message = json_decode($message, true);
                if ($message['className'] && class_exists($message['className'])) {
                    $class = new $message['className']($modx);
                    $method = $message['method'];
                    if (method_exists($class, $method)) {
                        unset($message['className'], $message['method']);
                        if ($oldUser->get('id') != $message['user_id']) {
                            $modx->user = $modx->getObject('modUser', $message['user_id']);
                        }
                        $class->$method($message);
                        $modx->user = $oldUser;
                    }
                }
            }
        }
        break;
}
