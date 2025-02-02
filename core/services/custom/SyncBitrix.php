<?php

namespace CustomServices;

class SyncBitrix
{
    /**
     * @var \Modx
     */
    public \ModX $modx;

    /**
     * @var QueueManager
     */
    public QueueManager $qm;


    private bool $debug = true;

    public function __construct(\Modx $modx)
    {
        $this->modx = $modx;
        $this->initialize();
    }

    /**
     * @return void
     */
    protected function initialize(): void
    {
        $this->qm = new QueueManager($this->modx);
    }

    public function addToQueueSync(array $data, array $properties = []): array
    {
        $queueData = [
            'className' => 'CustomServices\SyncBitrix',
            'method' => 'syncSiteProductsWithBitrix',
            'session_id' => session_id(),
        ];
        $this->qm->addToQueue('CustomServices', $queueData);

        return ['success' => true, 'message' => 'Синхронизация начата!', 'data' => []];
    }

    /**
     * @return void
     */
    public function syncSiteProductsWithBitrix(?array $data = []): array
    {
        sleep(60);

        $queueData = [
            'eventName' => 'sync:bitrix:finished',
            'message' => 'Синхронизация данных курсов с Б24 завершена!',
        ];
        $this->qm->addToQueue($data['session_id'], $queueData);
    }
}
