<?php

namespace GhostWalker\Jobber;

class JobberServer
{
    /**
     * @var \Nette\Loaders\RobotLoader
     */
    protected \Nette\Loaders\RobotLoader $loader;

    /**
     * @var \React\Socket\SocketServer
     */
    protected \React\Socket\SocketServer $socket;

    /**
     * @return void
     */
    public static function bootSystem(): void
    {
        new self::class;
    }

    public function __construct()
    {
        $this->loader = new \Nette\Loaders\RobotLoader();
        $this->socket = new \React\Socket\SocketServer('127.0.0.1:8080');

        $this->addJobDirectory();
        $this->bootSocket();
    }

    /**
     * @return void
     */
    protected function bootSocket(): void
    {
        $this->socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->on('data', function ($data) use ($connection) {
                $data = json_decode($data);
                $connection->close();
                $this->addTask($data->classname, $data->data);
            });
        });
    }

    /**
     * @return void
     */
    protected function addJobDirectory(): void
    {
        $this->loader->setTempDirectory(__DIR__ . '/temp');
        $this->loader->addDirectory(__DIR__ . '/jobs/');
        $this->loader->register();
    }

    /**
     * @param string $className
     * @param array $data
     * @return void
     */
    protected function addTask(string $className, array $data): void
    {
        $obj = new $className;
        $obj->handler($data);
    }
}

JobberServer::bootSystem();