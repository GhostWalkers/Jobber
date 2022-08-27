<?php

namespace GhostWalker\Jobber;

class Jobber
{
    /**
     * @var \React\Socket\Connector
     */
    protected \React\Socket\Connector $connector;

    public function __construct()
    {
        $this->connector = new \React\Socket\Connector();
    }

    /**
     * @param mixed $data
     * @return void
     */
    protected function sendDataToServer(mixed $data): void
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

