<?php
/**
 * @var modX $modx
 */

require_once MODX_CORE_PATH . 'services/vendor/autoload.php';
require_once MODX_CORE_PATH . 'components/sendit/services/sendit.class.php';

switch ($modx->event->name) {
    case 'OnManagerPageInit':
        $jsConfigPath = $modx->getOption('si_js_config_path', '', './sendit.inc.js');
        $cookies = !empty($_COOKIE['SendIt']) ? json_decode($_COOKIE['SendIt'], 1) : [];

        $data = [
            'sitoken' => md5($_SERVER['REMOTE_ADDR'] . time()),
            'sitrusted' => '0',
            'sijsconfigpath' => $jsConfigPath
        ];
        SendIt::setSession($modx, [
            'sitoken' => $data['sitoken'],
            'sendingLimits' => []
        ]);

        $data = array_merge($cookies, $data);
        setcookie('SendIt', json_encode($data), 0, '/');

        $modx->regClientStartupHTMLBlock(
            '            
            <script type="module" src="/assets/project_files/js/mgr/sse.js"></script>
            '
        );
        $modx->regClientStartupHTMLBlock(
            '
            <script type="module" src="/assets/components/sendit/js/web/sendit.js"></script>            
            '
        );
        $modx->regClientStartupHTMLBlock(
            '            
            <script type="module" src="/assets/project_files/js/mgr/admin.js"></script>
            '
        );
        break;
}
