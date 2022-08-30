<?php

namespace GhostWalker\Jobber;


use React\EventLoop\Loop;

class JobberServer
{
    /**
     * @var \Nette\Loaders\RobotLoader
     */
    protected \Nette\Loaders\RobotLoader $loader;

    /**
     * @var string
     */
    public static string $ipPort = '127.0.0.1:8080';

    /**
     * @var string
     */
    public static string $directory = __DIR__ . '/jobs';

    /**
     * @var \React\Socket\SocketServer
     */
    protected \React\Socket\SocketServer $socket;

    /**
     * @var array
     */
    protected array $tasks = [];

    /**
     * @param array $settings
     * @return void
     */
    public static function bootSystem(array $settings = []): void
    {
        new self();
    }


    public function __construct()
    {
        $this->loader = new \Nette\Loaders\RobotLoader();
        $this->socket = new \React\Socket\SocketServer(static::$ipPort);

        $this->addJobDirectory();
        $this->bootSocket();
        $this->loopTask();
    }

    /**
     * @return void
     */
    protected function bootSocket(): void
    {
        $this->socket->on('connection', function (\React\Socket\ConnectionInterface $connection) {
            $connection->on('data', function ($data) use ($connection) {
                $connection->close();
                $data = json_decode($data);
                $this->tasks[] = [$data->classname, $data->data];
            });
        });
    }

    /**
     * @return void
     */
    protected function addJobDirectory(): void
    {
        $this->loader->setTempDirectory(__DIR__ . '/temp');
        $this->loader->addDirectory(static::$directory);
        $this->loader->register();
    }

    /**
     * @return void
     */
    protected function loopTask(): void
    {
        $loop = Loop::get();

        $loop->addPeriodicTimer(1, function (): void {
            if ($this->tasks === []) {
                return;
            }

            foreach ($this->tasks as $key => $data) {
                $obj = new $data[0]($data[1]);
                unset($this->tasks[$key]);
            }
        });
    }
}

