<?php
/**
 * boot JobberServer Class
 */
use GhostWalker\Jobber\JobberServer;

require_once __DIR__ . 'vendor/autoload.php';

JobberServer::$directory = __DIR__ . '/jobs';
JobberServer::$ipPort = '127.0.0.1:6969';
JobberServer::bootSystem();
