<?php

class modDashboardWidgetSyncBitrix extends modDashboardWidgetInterface
{
    public $version = '1.0';

    public function render()
    {
        $pdoTools = $this->modx->getService('pdoTools');
        return $pdoTools->getChunk('@FILE chunks/widgets/sync_bitrix.tpl', []);
    }
}

return 'modDashboardWidgetSyncBitrix';
