<?php
/**
 * boot JobberServer Class
 */

require_once 'vendor/autoload.php';

require_once __DIR__ . '/src/Server/Server.php';

GhostWalker\Jobber\JobberServer::bootSystem([
    'jobDirectory' => __DIR__ . '/jobs',
]);
