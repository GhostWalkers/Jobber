<?php
/**
 * boot JobberServer Class
 */

require_once __DIR__ . 'vendor/autoload.php';

\GhostWalker\Jobber\JobberServer::$directory = __DIR__ . '/jobs';
GhostWalker\Jobber\JobberServer::bootSystem();
