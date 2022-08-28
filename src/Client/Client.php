<?php

namespace GhostWalker\Jobber;

use JetBrains\PhpStorm\NoReturn;

class JobberClient
{
    /**
     * @var \React\Socket\Connector
     */
    protected \React\Socket\Connector $connector;

    /**
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->connector = new \React\Socket\Connector();
    }

    /**
     * @param string $data
     * @return void
     */
    protected function sendDataToServer(string $data): void
    {
        $this->connector->connect('127.0.0.1:8080')->then(function (\React\Socket\ConnectionInterface $connection) use ($data) {
            $connection->write($data);
        }, static function (\Exception $e) {
            throw new \RuntimeException('Error: ' . $e->getMessage() . PHP_EOL);
        });
    }

    /**
     * @param string $className
     * @param array $data
     * @return void
     */
    public function addTask(string $className, array $data = []): void
    {
        $this->sendDataToServer(json_encode([
            'classname' => $className,
            'data' => $data,
        ]));
    }
}

