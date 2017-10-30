<?php
use PruneMazui\Tetrice\Application;
use PruneMazui\Tetrice\Controller\ControllerKeyboard;

require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'fps' => 60 // 20分の1秒×1000（ミリ秒）
];

return new Application(new ControllerKeyboard(), $config);
