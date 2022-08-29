<?php

namespace GhostWalker\Jobber;

use JetBrains\PhpStorm\NoReturn;

use React\EventLoop\Loop;
use function React\Async\async;
use function React\Async\series;


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

    protected array $tasks = [];

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @param array $settings
     * @return void
     */
    public static function bootSystem(array $settings = []): void
    {
        new self($settings);
    }


    public function __construct(array $settings)
    {
        $this->loader = new \Nette\Loaders\RobotLoader();
        $this->socket = new \React\Socket\SocketServer('127.0.0.1:8080');
        $this->settings = $settings;

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
        $this->loader->addDirectory($this->settings['jobDirectory']);
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

                $obj = new $data[0];
                $obj->handler($data[1]);
                unset($key);
            }
        });
    }
}

