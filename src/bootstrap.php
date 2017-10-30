<?php
use PruneMazui\Tetrice\Application;
use PruneMazui\Tetrice\Controller\ControllerKeyboard;
use PruneMazui\Tetrice\Config;

require_once __DIR__ . '/../vendor/autoload.php';

return new Application(new ControllerKeyboard(), new Config());
