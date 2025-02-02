<?php
/**
 * @author Arthur Shevchenko
 * @description Сервис для работы с менеджером очередей Modx
 */

namespace CustomServices;

class QueueManager
{

    /**
     * @var \modX
     */
    public \modX $modx;
    /**
     * @var object
     */
    private object $QM;
    /**
     * @var Logging
     */
    private Logging $logging;

    /**
     * @param \modX $modx
     */
    public function __construct(\modX $modx)
    {
        $this->modx = $modx;
        $this->initialize();
    }

    /**
     * @return void
     */
    private function initialize()
    {
        $registryClass = $this->modx->getOption('registry_class', null, 'registry.modFileRegister');
        $queueName = $this->modx->getOption('queue_name', null, 'CustomServices');
        $registry = $this->modx->getService('registry', 'registry.modRegistry');
        $this->QM = $registry->getRegister($queueName, $registryClass);
        $this->logging = new Logging();
        $this->logging->setPath('queue_manager.txt');
    }

    /**
     * @param string $branch
     * @param bool|null $remove
     * @return array
     */
    public function getMessages(string $branch, ?bool $remove = true): array
    {
        if (!$branch) {
            $this->logging->write(__METHOD__, 'Branch is empty');
            return [];
        }

        $branch = '/' . $branch . '/';
        $this->QM->subscribe($branch);
        $messages = $this->QM->read([
            'poll_limit' => 1,
            'msg_limit' => 1000,
            'include_keys' => true,
            'remove_read' => $remove
        ]);
        $this->QM->unsubscribe($branch);

        return $messages;
    }

    /**
     * @param $branch
     * @param $data
     * @return bool
     */
    public function addToQueue($branch, $data): bool
    {
        if (!$data) {
            $this->logging->write(__METHOD__, 'Data is empty');
            return false;
        }
        if (!$branch) {
            $this->logging->write(__METHOD__, 'Branch is empty');
            return false;
        }

        $branch = '/' . $branch . '/';
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $this->QM->subscribe($branch);
        $this->QM->send($branch, $data);
        $this->QM->unsubscribe($branch);

        return true;
    }
}
