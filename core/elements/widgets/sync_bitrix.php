<?php

class modDashboardWidgetSyncBitrix extends modDashboardWidgetInterface
{
    public function render()
    {
        $pdoTools = $this->modx->getService('pdoTools');
        return $pdoTools->getChunk('@FILE chunks/widgets/sync_bitrix.tpl', []);
    }
}

return 'modDashboardWidgetSyncBitrix';
