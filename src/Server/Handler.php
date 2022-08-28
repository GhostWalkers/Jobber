<?php

namespace GhostWalker\Jobber;

require_once 'Server.php';

while (true)
{
    if (JobberServer::$tasks === [])
    {
        sleep(3);
    }

    foreach (JobberServer::$tasks as $key => $task) {
        $obj = new $task['className'];
        $obj->handler($task['data']);
        unset($key);
    }
}