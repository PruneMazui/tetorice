<?php
use PruneMazui\Tetrice\Application;

require_once __DIR__ . '/../vendor/autoload.php';

define('_DEBUG', true);

$config = [
    'fps' => 60 // 20分の1秒×1000（ミリ秒）
];

return new Application($config);
