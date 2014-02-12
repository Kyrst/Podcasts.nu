<?php
ini_set('memory_limit', '128M');

require __DIR__ . '/../bootstrap/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/start.php';

$app->run();

$app->shutdown();