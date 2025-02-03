<?php
/**
 * @author Arthur Shevchenko
 * @description Сервис для логирования работы скриптов
 */
namespace CustomServices;

class Logging
{
    /**
     * @var string
     */
    public string $path;
    /**
     * @var bool|null
     */
    private bool $debug;

    /**
     * @param bool|null $debug
     */
    public function __construct(?bool $debug = true)
    {
        $this->debug = $debug;
        $this->initialize();
    }

    /**
     * @return void
     */
    private function initialize(){
        $this->setPath('log.txt');
    }

    public function setPath(?string $fileName = '', ?string $dir = '') {
        $dir = $dir ?: dirname(__FILE__, 2) . '/logs/'. date('d-m-Y') . '/';
        $this->path = $dir . $fileName;
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * @param $method
     * @param $msg
     * @param $data
     * @param $noDate
     * @return void
     */
    public function write($method, $msg, $data = [], $noDate = false)
    {
        if ($this->debug) {
            if (!$noDate) {
                $date = date('d.m.Y H:i:s');
                $text = "**$date** [$method] $msg" . PHP_EOL;
            } else {
                $text = PHP_EOL . "*************************** [$method] $msg ***************************" . PHP_EOL;
            }


            if (!empty($data)) {
                file_put_contents($this->path, $text . print_r($data, 1) . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents($this->path, $text, FILE_APPEND);
            }
        }
    }
}
